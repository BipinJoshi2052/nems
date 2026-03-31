<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Auth\ForgotPasswordController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\HomeController;
use App\Http\Controllers\Tenant\SubscriptionController;
use App\Http\Middleware\ResolveTenant;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
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
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    ResolveTenant::class,
    'tenant.status',
])
// Only match subdomains of nems.com for tenant routes
    // ->domain('{subdomain}.nems.com')
    ->group(function (): void {
        // Tenant Home
        Route::get('/', [HomeController::class, 'index'])->name('tenant.home');

        // Guest Routes (Blade)
        Route::middleware('guest')->group(function () {
            Route::get('/login', [LoginController::class, 'showLoginForm'])->name('tenant.login');
            Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('tenant.password.reset');
        });

        // Authenticated Routes (Blade)
        Route::middleware('auth')->group(function () {
            Route::get('/subscription', [SubscriptionController::class, 'index'])->name('tenant.subscription.index');
        });

        // Vue SPA Dashboard (Public entry, SPA handles internal auth)
        Route::get('/dashboard/{any?}', function () {
            return view('tenant.spa');
        })->where('any', '.*')->name('tenant.dashboard');

        // API Auth Endpoints
        Route::prefix('api/auth')->group(function () {
            Route::post('/login', [LoginController::class, 'login']);
            Route::post('/refresh', [LoginController::class, 'refresh']);
            Route::post('/logout', [LoginController::class, 'logout']);
            Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
            Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
        });
    });
