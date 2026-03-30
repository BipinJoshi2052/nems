<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Contracts\CalendarServiceInterface;
use App\Core\Contracts\PushNotificationServiceInterface;
use App\Core\Contracts\SmsServiceInterface;
use App\Core\Contracts\StorageServiceInterface;
use App\Core\Services\AttachmentService;
use App\Core\Services\LocalStorageService;
use App\Core\Services\LogPushService;
use App\Core\Services\LogSmsService;
use App\Core\Services\NepaliCalendarService;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StorageServiceInterface::class, LocalStorageService::class);
        $this->app->singleton(CalendarServiceInterface::class, NepaliCalendarService::class);
        $this->app->singleton(SmsServiceInterface::class, LogSmsService::class);
        $this->app->singleton(PushNotificationServiceInterface::class, LogPushService::class);
        $this->app->singleton(AttachmentService::class, AttachmentService::class);
    }

    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(fn () => route('platform.dashboard'));

        RateLimiter::for('platform-login', function (Request $request) {
            return Limit::perMinutes(15, 5)->by($request->ip());
        });
    }
}
