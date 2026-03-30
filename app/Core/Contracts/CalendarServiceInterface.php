<?php

declare(strict_types=1);

namespace App\Core\Contracts;

use App\Core\ValueObjects\NepaliDate;
use Carbon\Carbon;

interface CalendarServiceInterface
{
    public function toBs(Carbon $ad): NepaliDate;

    public function toAd(NepaliDate $bs): Carbon;

    /**
     * Nepal government fiscal year label, e.g. "2081/82" (Shrawan–Asar).
     */
    public function currentFiscalYear(): string;

    public function formatBs(NepaliDate $date, string $format = 'Y-m-d'): string;
}
