<?php

declare(strict_types=1);

namespace App\Enums;

enum ImageVariantEnum: string
{
    case Original = 'original';
    case Thumbnail = 'thumbnail';
}
