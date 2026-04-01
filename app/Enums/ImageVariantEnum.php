<?php

declare(strict_types=1);

namespace App\Enums;

enum ImageVariantEnum: int
{
    case Original = 1;
    case Thumbnail = 2;
}
