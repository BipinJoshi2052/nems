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
])->group(function (): void {
    //
});
