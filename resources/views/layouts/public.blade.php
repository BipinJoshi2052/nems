<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    @if(isset($seo))
        {!! $seo->renderTags() !!}
        {!! $seo->renderJsonLd() !!}
    @else
        <title>{{ config('app.name', 'Nepal Education System') }}</title>
    @endif

    <!-- Fonts -->
    <!-- Removed external font to comply with CSP, relying on system fonts (Bootstrap default) -->

    <!-- Bootstrap 5 & Icons -->
    <link href="{{ asset('css/platform/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/platform/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: 800;
            color: #0d6efd !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>{{ __('EduNepal') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic" aria-controls="navbarPublic" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('features') }}">{{ __('Features') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pricing') }}">{{ __('Pricing') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">{{ __('About') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">{{ __('Blog') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    @php
                        $user = Auth::user() ?? Auth::guard('platform')->user() ?? Auth::guard('customer')->user();
                        $isAdmin = $user && $user->isPlatformAdmin();
                    @endphp

                    @if($user)
                        @if($isAdmin)
                            <a href="{{ route('platform.dashboard') }}" class="btn btn-outline-primary me-2">
                                <i class="bi bi-speedometer2 me-1"></i>{{ __('Dashboard') }}
                            </a>
                            <form method="POST" action="{{ route('platform.logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 ms-2">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        @else
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle fs-4 me-2"></i> {{ $user->name ?? $user->email }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>{{ __('Profile') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('platform.logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('platform.login') }}" class="btn btn-outline-primary me-2">{{ __('Login') }}</a>
                        <a href="{{ route('site.register') }}" class="btn btn-primary">{{ __('Get Started') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-5 mt-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold mb-3"><i class="bi bi-mortarboard-fill text-primary me-2"></i>{{ __('EduNepal') }}</h5>
                    <p class="text-muted">{{ __('Transforming education management across Nepal with our modern cloud platform.') }}</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">{{ __('Platform') }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('features') }}" class="text-muted text-decoration-none">{{ __('Features') }}</a></li>
                        <li><a href="{{ route('pricing') }}" class="text-muted text-decoration-none">{{ __('Pricing') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold mb-3">{{ __('Company') }}</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}" class="text-muted text-decoration-none">{{ __('About Us') }}</a></li>
                        <li><a href="{{ route('blog.index') }}" class="text-muted text-decoration-none">{{ __('Blog') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold mb-3">{{ __('Contact') }}</h6>
                    <ul class="list-unstyled text-muted">
                        <li><i class="bi bi-envelope me-2"></i> support@edunepal.example.com</li>
                        <li><i class="bi bi-geo-alt me-2"></i> Kathmandu, Nepal</li>
                    </ul>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ __('EduNepal. All rights reserved.') }}</p>
                <ul class="list-unstyled d-flex mb-0">
                    <li class="ms-3"><a class="link-dark" href="#"><i class="bi bi-twitter fs-5"></i></a></li>
                    <li class="ms-3"><a class="link-dark" href="#"><i class="bi bi-linkedin fs-5"></i></a></li>
                    <li class="ms-3"><a class="link-dark" href="#"><i class="bi bi-facebook fs-5"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('js/platform/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
