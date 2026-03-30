<?php

declare(strict_types=1);

namespace App\Enums;

enum TenantStatusEnum: string
{
    case PendingOtp = 'pending_otp';
    case PendingPassword = 'pending_password';
    case Provisioning = 'provisioning';
    case Trial = 'trial';
    case Active = 'active';
    case Expired = 'expired';
    case Suspended = 'suspended';
}
