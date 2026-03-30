<?php

declare(strict_types=1);

use App\Platform\Controllers\Auth\LoginController;
use App\Platform\Controllers\Auth\PasswordController;
use App\Platform\Controllers\Auth\TwoFactorChallengeController;
use App\Platform\Controllers\Auth\TwoFactorSetupController;
use App\Platform\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:platform')->group(function (): void {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])
        ->middleware('throttle:platform-login')
        ->name('login.attempt');
    Route::get('two-factor-challenge', [TwoFactorChallengeController::class, 'show'])->name('two-factor.challenge');
    Route::post('two-factor-challenge', [TwoFactorChallengeController::class, 'store'])->name('two-factor.verify');
});

Route::middleware('auth:platform')->group(function (): void {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth:platform', 'platform.admin'])->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('tenants', [\App\Platform\Controllers\TenantController::class, 'index'])->name('tenants.index');
    Route::get('tenants/create', [\App\Platform\Controllers\TenantController::class, 'create'])->name('tenants.create');
    Route::post('tenants', [\App\Platform\Controllers\TenantController::class, 'store'])->name('tenants.store');
    Route::get('tenants/{tenant}', [\App\Platform\Controllers\TenantController::class, 'show'])->name('tenants.show');
    Route::patch('tenants/{tenant}/toggle-status', [\App\Platform\Controllers\TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');

    Route::get('password/edit', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('two-factor/setup', [TwoFactorSetupController::class, 'show'])->name('two-factor.setup');
    Route::post('two-factor/setup', [TwoFactorSetupController::class, 'store'])->name('two-factor.setup.store');
});
