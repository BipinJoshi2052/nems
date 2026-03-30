@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white text-center py-5">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-4">{{ __('Manage Your Educational Institution with Ease') }}</h1>
        <p class="lead mb-5 opacity-75">{{ __('EduNepal provides a comprehensive, multi-tenant cloud platform for schools and colleges.') }}</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('pricing') }}" class="btn btn-light btn-lg">{{ __('View Plans') }}</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">{{ __('Contact Sales') }}</a>
        </div>
    </div>
</section>

<!-- Features Overview -->
<section class="py-5">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">{{ __('Why Choose EduNepal?') }}</h2>
            <p class="text-muted">{{ __('Everything you need to run your institution smoothly.') }}</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                        <h4 class="card-title mt-3 fw-bold">{{ __('Student Management') }}</h4>
                        <p class="card-text text-muted">{{ __('Keep track of student records, attendance, and grades effortlessly.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-cash-coin text-success" style="font-size: 3rem;"></i>
                        <h4 class="card-title mt-3 fw-bold">{{ __('Fee Tracking') }}</h4>
                        <p class="card-text text-muted">{{ __('Automated billing and real-time payment tracking for parents.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-building text-info" style="font-size: 3rem;"></i>
                        <h4 class="card-title mt-3 fw-bold">{{ __('Multi-Tenant') }}</h4>
                        <p class="card-text text-muted">{{ __('Get your own dedicated portal and seamless subdomain experience.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('features') }}" class="btn btn-outline-primary">{{ __('See All Features') }} <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-light py-5">
    <div class="container text-center py-4">
        <h3 class="fw-bold mb-3">{{ __('Ready to modernize your institution?') }}</h3>
        <p class="text-muted mb-4">{{ __('Join hundreds of schools using EduNepal today.') }}</p>
        @auth
            <a href="{{ route('platform.dashboard') }}" class="btn btn-primary btn-lg">{{ __('Go to Platform') }}</a>
        @else
            <a href="{{ route('site.register') }}" class="btn btn-primary btn-lg">{{ __('Get Started Now') }}</a>
        @endauth
    </div>
</section>
@endsection
