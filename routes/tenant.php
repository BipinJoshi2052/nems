<?php

declare(strict_types=1);

use App\Http\Controllers\Tenant\Auth\ForgotPasswordController;
use App\Http\Controllers\Tenant\Auth\LoginController;
use App\Http\Controllers\Tenant\HomeController;
use App\Http\Controllers\Tenant\SubscriptionController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\NotificationController;
use App\Http\Controllers\Tenant\SetupController;
use App\Http\Controllers\Tenant\AcademicYearController;
use App\Http\Controllers\Tenant\StaffController;
use App\Http\Controllers\Tenant\StudentController;
use App\Http\Controllers\Tenant\ParentController;
use App\Http\Controllers\Tenant\ClassController;
use App\Http\Controllers\Tenant\SectionController;
use App\Http\Controllers\Tenant\SubjectController;
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

        // Protected API Endpoints
        Route::middleware('auth.jwt')->group(function () {
            Route::put('/api/user/preferences', [UserController::class, 'updatePreferences']);
            Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount']);
            Route::get('/api/notifications', [NotificationController::class, 'index']);
            Route::patch('/api/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::post('/api/notifications/read-all', [NotificationController::class, 'markAllRead']);

            // Onboarding Setup Endpoints
            Route::prefix('api/setup')->group(function () {
                Route::get('/progress', [SetupController::class, 'getProgress']);
                Route::post('/save-progress', [SetupController::class, 'saveProgress']);
                Route::post('/school-info', [SetupController::class, 'saveSchoolInfo']);
                Route::post('/subjects', [SetupController::class, 'saveSubjects']);
                Route::post('/classes', [SetupController::class, 'saveClasses']);
                Route::post('/invite-teachers', [SetupController::class, 'inviteTeachers']);
                Route::post('/first-student', [SetupController::class, 'saveFirstStudent']);
                Route::patch('/complete', [SetupController::class, 'completeSetup']);
            });

            // Academic Year Endpoints
            Route::apiResource('/api/academic-years', AcademicYearController::class);
            Route::get('/api/setup/academic-years/previous-classes', [AcademicYearController::class, 'previousClasses']);
            Route::get('/api/setup/academic-years/previous-assignments', [AcademicYearController::class, 'previousAssignments']);
            Route::post('/api/setup/academic-years/validate', [AcademicYearController::class, 'validateData']);

            // Class, Section & Subject Management
            Route::apiResource('/api/classes', ClassController::class);
            Route::apiResource('/api/sections', SectionController::class);
            Route::apiResource('/api/subjects', SubjectController::class);

            // Staff Management
            Route::get('/api/staff', [StaffController::class, 'index']);
            Route::post('/api/staff/invite', [StaffController::class, 'invite']);
            Route::get('/api/staff/{user}', [StaffController::class, 'show']);
            Route::patch('/api/staff/{user}', [StaffController::class, 'update']);
            Route::delete('/api/staff/{user}', [StaffController::class, 'destroy']);
            Route::post('/api/staff/{user}/class-assignments', [StaffController::class, 'assignClasses']);

            // Student Management
            Route::get('/api/students', [StudentController::class, 'index']);
            Route::post('/api/students', [StudentController::class, 'store']);
            Route::get('/api/students/{student}', [StudentController::class, 'show']);
            Route::patch('/api/students/{student}', [StudentController::class, 'update']);
            Route::patch('/api/students/{student}/status', [StudentController::class, 'updateStatus']);
            Route::delete('/api/students/{student}', [StudentController::class, 'destroy']);

            // Parent Management
            Route::apiResource('/api/parents', ParentController::class);
            Route::post('/api/parents/link-to-student/{student}', [ParentController::class, 'linkToStudent']);
        });

        // Serve Tenant Storage Files
        Route::get('/storage/{path}', function ($path) {
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            if (!$disk->exists($path)) {
                abort(404);
            }
            return $disk->response($path);
        })->where('path', '.*')->name('tenant.storage');
    });
