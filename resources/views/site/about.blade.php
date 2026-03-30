@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="fw-bold mb-4">{{ __('About EduNepal') }}</h1>
            <p class="lead text-muted mb-5">{{ __('We are on a mission to digitize educational institutions across Nepal by providing world-class, affordable, and easy-to-use software.') }}</p>
            
            <img src="https://via.placeholder.com/800x400.png?text=EduNepal+Team" alt="Team" class="img-fluid rounded shadow-sm mb-5">
            
            <h3 class="fw-bold mb-3">{{ __('Our Story') }}</h3>
            <p class="text-muted text-start mb-4">{{ __('Founded in 2026, EduNepal was built out of necessity. After seeing countless schools struggle with fragmented systems and messy paperwork, we built a modern multi-tenant cloud platform to bring everything under one roof. Today, we power institutions of all sizes across the country.') }}</p>

            <h3 class="fw-bold mb-3">{{ __('Our Values') }}</h3>
            <div class="row text-start mt-4 g-4">
                <div class="col-md-6">
                    <div class="d-flex">
                        <i class="bi bi-shield-check text-primary fs-3 me-3"></i>
                        <div>
                            <h5 class="fw-bold">{{ __('Security First') }}</h5>
                            <p class="text-muted">{{ __('We ensure your institutional data is isolated, encrypted, and safe.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex">
                        <i class="bi bi-lightning-charge text-warning fs-3 me-3"></i>
                        <div>
                            <h5 class="fw-bold">{{ __('Innovation') }}</h5>
                            <p class="text-muted">{{ __('We continuously deploy updates to keep you ahead of the curve.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
