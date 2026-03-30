<?php

declare(strict_types=1);

namespace App\Enums;

enum StorageOperationEnum: string
{
    case Upload = 'upload';
    case Delete = 'delete';
}
