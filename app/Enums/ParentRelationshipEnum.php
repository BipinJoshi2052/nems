<?php

declare(strict_types=1);

namespace App\Enums;

enum ParentRelationshipEnum: string
{
    case Father = 'father';
    case Mother = 'mother';
    case Brother = 'brother';
    case Sister = 'sister';
    case Guardian = 'guardian';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
