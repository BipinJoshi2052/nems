<?php

declare(strict_types=1);

namespace App\Core\ValueObjects;

use InvalidArgumentException;

final readonly class NepaliDate
{
    public function __construct(
        public int $year,
        public int $month,
        public int $day,
    ) {}

    public static function fromYmd(string $ymd, string $separator = '-'): self
    {
        $parts = explode($separator, $ymd);
        if (count($parts) !== 3) {
            throw new InvalidArgumentException('Invalid BS date string.');
        }

        return new self(
            (int) $parts[0],
            (int) $parts[1],
            (int) $parts[2],
        );
    }

    public function toYmd(string $separator = '-'): string
    {
        return sprintf(
            '%04d%s%02d%s%02d',
            $this->year,
            $separator,
            $this->month,
            $separator,
            $this->day
        );
    }
}
