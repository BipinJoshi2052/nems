<?php

declare(strict_types=1);

use App\Http\Middleware\ResolveTenant;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    PreventAccessFromCentralDomains::class,
    InitializeTenancyByDomainOrSubdomain::class,
    ResolveTenant::class,
    'tenant.status',
])->group(function (): void {
    // Guest Routes (Blade)
    Route::middleware('guest')->group(function() {
        Route::get('/login', [\App\Http\Controllers\Tenant\Auth\LoginController::class, 'showLoginForm'])->name('tenant.login');
        Route::get('/reset-password/{token}', [\App\Http\Controllers\Tenant\Auth\ForgotPasswordController::class, 'showResetForm'])->name('tenant.password.reset');
    });

    // Authenticated Routes (Blade)
    Route::middleware('auth')->group(function() {
        Route::get('/subscription', [\App\Http\Controllers\Tenant\SubscriptionController::class, 'index'])->name('tenant.subscription.index');
        
        // Vue SPA Dashboard
        Route::get('/dashboard/{any?}', function () {
            return view('tenant.spa');
        })->where('any', '.*')->name('tenant.dashboard');
    });

    // API Auth Endpoints
    Route::prefix('api/auth')->group(function() {
        Route::post('/login', [\App\Http\Controllers\Tenant\Auth\LoginController::class, 'login']);
        Route::post('/forgot-password', [\App\Http\Controllers\Tenant\Auth\ForgotPasswordController::class, 'sendResetLink']);
        Route::post('/reset-password', [\App\Http\Controllers\Tenant\Auth\ForgotPasswordController::class, 'resetPassword']);
    });
});
