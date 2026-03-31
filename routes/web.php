<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\PublicController;
use App\Http\Controllers\Site\ProfileController;
use App\Http\Controllers\Site\RegisterController;
use App\Http\Controllers\Site\OtpController;
use App\Http\Controllers\Site\PasswordSetupController;
use App\Http\Controllers\Site\ProvisioningController;

Route::domain('nems.com')->group(function () {
    // Auth & Identity
    Route::get('/login', [\App\Platform\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/platform/login', [\App\Platform\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('platform.login');
    Route::post('/login', [\App\Platform\Controllers\Auth\LoginController::class, 'login'])->name('platform.login.attempt');
    Route::post('/logout', [\App\Platform\Controllers\Auth\LoginController::class, 'logout'])->name('platform.logout');

    Route::middleware('guest:platform')->group(function () {
        Route::get('/forgot-password', [\App\Platform\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('platform.password.request');
        Route::post('/forgot-password', [\App\Platform\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('platform.password.email');
        Route::get('/platform/reset-password/{token}', [\App\Platform\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('platform.password.reset');
        Route::post('/reset-password', [\App\Platform\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('platform.password.update');
    });

    Route::get('/api/check-subdomain', [RegisterController::class, 'checkSubdomainAvailability'])->name('api.check-subdomain');

    // Public Pages
    Route::get('/', [PublicController::class, 'home'])->name('home');
    Route::get('/features', [PublicController::class, 'features'])->name('features');
    Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');
    Route::get('/about', [PublicController::class, 'about'])->name('about');
    Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

    // Guest & Self-Signup Flow
    Route::middleware('guest:platform')->group(function () {
        Route::get('/register', [RegisterController::class, 'showForm'])->name('site.register');
        Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:60,1')->name('site.register.submit');

        Route::get('/verify-otp', [OtpController::class, 'showForm'])->name('site.verify-otp.show');
        Route::post('/verify-otp', [OtpController::class, 'verify'])->name('site.verify-otp.submit');

        // Admin Invitation Flow
        Route::get('/setup/{token}', [\App\Http\Controllers\Site\SetupTokenController::class, 'verify'])->name('site.setup-token.verify');

        Route::get('/setup-password', [PasswordSetupController::class, 'showForm'])->name('site.setup-password.show');
        Route::post('/setup-password', [PasswordSetupController::class, 'store'])->name('site.setup-password.submit');

        Route::get('/provisioning', [ProvisioningController::class, 'showLoader'])->name('site.provisioning');
    });
    Route::get('/platform/api/tenant/status', [ProvisioningController::class, 'status'])->name('site.provisioning.status');

    // Blog Pages
    Route::get('/blog', [PublicController::class, 'blogIndex'])->name('blog.index');
    Route::get('/blog/{slug}', [PublicController::class, 'blogShow'])->name('blog.show');

    // SEO Routes
    Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');
    Route::get('/robots.txt', [PublicController::class, 'robots'])->name('robots');

    // Authenticated User Profile
    Route::middleware(['auth:platform', 'platform.tenant'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    });

    // Platform Admin Routes (Existing)
    Route::prefix('platform')->name('platform.')->group(base_path('routes/platform.php'));
});
