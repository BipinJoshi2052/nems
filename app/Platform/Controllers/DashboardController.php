<?php

declare(strict_types=1);

namespace App\Platform\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('platform.dashboard');
    }
}
