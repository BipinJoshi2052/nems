<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case PlatformAdmin = 'platform_admin';
    case SchoolAdmin = 'school_admin';
    case Teacher = 'teacher';
    case Accountant = 'accountant';
    case Receptionist = 'receptionist';
    case Staff = 'staff';
}
