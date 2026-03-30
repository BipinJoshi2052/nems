<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\CalendarServiceInterface;
use App\Core\ValueObjects\NepaliDate;
use Carbon\Carbon;
use MilanTarami\NepaliCalendar\CalendarFunction;

class NepaliCalendarService implements CalendarServiceInterface
{
    private const AD_FORMAT = 'YYYY-MM-DD';

    private const AD_SEP = '-';

    public function toBs(Carbon $ad): NepaliDate
    {
        $adStr = $ad->format('Y-m-d');
        $result = CalendarFunction::adToBs($adStr, self::AD_FORMAT, self::AD_SEP);

        return NepaliDate::fromYmd($result['BS_DATE']);
    }

    public function toAd(NepaliDate $bs): Carbon
    {
        $bsStr = CalendarFunction::dateResponseInFormat(
            $bs->year,
            $bs->month,
            $bs->day,
            self::AD_FORMAT,
            self::AD_SEP
        );
        $result = CalendarFunction::bsToAd($bsStr, self::AD_FORMAT, self::AD_SEP);

        return Carbon::parse($result['AD_DATE'])->startOfDay();
    }

    public function currentFiscalYear(): string
    {
        $bs = $this->toBs(Carbon::now('Asia/Kathmandu'));
        $month = $bs->month;
        $year = $bs->year;

        if ($month >= 4) {
            $start = $year;
            $end = $year + 1;
        } else {
            $start = $year - 1;
            $end = $year;
        }

        return sprintf('%d/%02d', $start, $end % 100);
    }

    public function formatBs(NepaliDate $date, string $format = 'Y-m-d'): string
    {
        $replacements = [
            'Y' => sprintf('%04d', $date->year),
            'm' => sprintf('%02d', $date->month),
            'd' => sprintf('%02d', $date->day),
        ];

        return strtr($format, $replacements);
    }
}
