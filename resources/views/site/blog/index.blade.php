@extends('layouts.public')

@section('content')
<div class="bg-light py-5">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">{{ __('EduNepal Blog') }}</h1>
        <p class="lead text-muted">{{ __('Insights, tips, and news for educational institutions.') }}</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <!-- Dummy Blog Post 1 -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://via.placeholder.com/400x200.png?text=EdTech" class="card-img-top" alt="Blog Image">
                <div class="card-body">
                    <span class="badge bg-primary mb-2">{{ __('Technology') }}</span>
                    <h5 class="card-title fw-bold"><a href="{{ route('blog.show', '5-ways-to-modernize') }}" class="text-dark text-decoration-none">{{ __('5 Ways to Modernize Your School in 2026') }}</a></h5>
                    <p class="card-text text-muted">{{ __('Discover actionable steps to bring your institution into the digital age...') }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-muted small pb-3">
                    <i class="bi bi-calendar3 me-1"></i> {{ __('March 29, 2026') }}
                </div>
            </div>
        </div>

        <!-- Dummy Blog Post 2 -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://via.placeholder.com/400x200.png?text=Management" class="card-img-top" alt="Blog Image">
                <div class="card-body">
                    <span class="badge bg-success mb-2">{{ __('Management') }}</span>
                    <h5 class="card-title fw-bold"><a href="{{ route('blog.show', 'streamlining-admissions') }}" class="text-dark text-decoration-none">{{ __('Streamlining the Admissions Process') }}</a></h5>
                    <p class="card-text text-muted">{{ __('Learn how digital forms can save hundreds of hours during peak enrollment periods...') }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-muted small pb-3">
                    <i class="bi bi-calendar3 me-1"></i> {{ __('March 15, 2026') }}
                </div>
            </div>
        </div>

        <!-- Dummy Blog Post 3 -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://via.placeholder.com/400x200.png?text=News" class="card-img-top" alt="Blog Image">
                <div class="card-body">
                    <span class="badge bg-info mb-2">{{ __('News') }}</span>
                    <h5 class="card-title fw-bold"><a href="{{ route('blog.show', 'edunepal-v2-release') }}" class="text-dark text-decoration-none">{{ __('Announcing EduNepal Phase 2') }}</a></h5>
                    <p class="card-text text-muted">{{ __('We are excited to announce new modules rolling out to all premium tenants next month.') }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-muted small pb-3">
                    <i class="bi bi-calendar3 me-1"></i> {{ __('March 01, 2026') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
