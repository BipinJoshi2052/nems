@extends('layouts.public')

@section('content')
<div class="bg-primary text-white py-5 text-center">
    <div class="container py-4">
        <h1 class="display-5 fw-bold">{{ __('Simple, Transparent Pricing') }}</h1>
        <p class="lead opacity-75">{{ __('Choose the plan that fits your institution.') }}</p>
    </div>
</div>

<div class="container py-5">
    
    <div class="d-flex justify-content-center mb-5 align-items-center">
        <span class="fs-5 me-3" id="lblMonthly">{{ __('Monthly') }}</span>
        <div class="form-check form-switch fs-4">
            <input class="form-check-input" type="checkbox" role="switch" id="billingToggle">
        </div>
        <span class="fs-5 ms-3 text-muted" id="lblAnnually">{{ __('Annually (Save 20%)') }}</span>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Basic Plan -->
        <div class="col-md-4">
            <div class="card h-100 border border-2 text-center rounded-3 shadow-sm">
                <div class="card-header py-3 bg-white border-bottom-0">
                    <h4 class="my-0 fw-normal">{{ __('Basic') }}</h4>
                </div>
                <div class="card-body">
                    <h1 class="card-title pricing-card-title mb-4">
                        <span class="price-monthly">रु 5,000</span>
                        <span class="price-annually d-none">रु 48,000</span>
                        <small class="text-muted fw-light billing-term">/{{ __('mo') }}</small>
                    </h1>
                    <ul class="list-unstyled mt-3 mb-4 text-start px-3">
                        <li class="mb-2"><i class="bi bi-check text-success me-2 fs-5"></i>{{ __('Up to 500 Students') }}</li>
                        <li class="mb-2"><i class="bi bi-check text-success me-2 fs-5"></i>{{ __('Core Admin Modules') }}</li>
                        <li class="mb-2"><i class="bi bi-check text-success me-2 fs-5"></i>{{ __('Community Support') }}</li>
                    </ul>
                    <a href="{{ route('site.register') }}" class="w-100 btn btn-lg btn-outline-primary">{{ __('Get Started') }}</a>
                </div>
            </div>
        </div>
        
        <!-- Premium Plan -->
        <div class="col-md-4">
            <div class="card h-100 border-primary border-2 text-center rounded-3 shadow">
                <div class="card-header py-3 text-white bg-primary border-primary">
                    <h4 class="my-0 fw-normal">{{ __('Premium') }} <i class="bi bi-star-fill text-warning fs-5 ms-1"></i></h4>
                </div>
                <div class="card-body">
                    <h1 class="card-title pricing-card-title mb-4">
                        <span class="price-monthly">रु 12,000</span>
                        <span class="price-annually d-none">रु 115,000</span>
                        <small class="text-muted fw-light billing-term">/{{ __('mo') }}</small>
                    </h1>
                    <ul class="list-unstyled mt-3 mb-4 text-start px-3">
                        <li class="mb-2"><i class="bi bi-check text-success me-2 fs-5"></i>{{ __('Unlimited Students') }}</li>
                        <li class="mb-2"><i class="bi bi-check text-success me-2 fs-5"></i>{{ __('All Modules + HR + Inventory') }}</li>
                        <li class="mb-2"><i class="bi bi-check text-success me-2 fs-5"></i>{{ __('Custom Domain Support') }}</li>
                        <li class="mb-2"><i class="bi bi-check text-success me-2 fs-5"></i>{{ __('Priority Support') }}</li>
                    </ul>
                    <a href="{{ route('site.register') }}" class="w-100 btn btn-lg btn-primary">{{ __('Get Premium') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('billingToggle');
        const monthlyPrices = document.querySelectorAll('.price-monthly');
        const annualPrices = document.querySelectorAll('.price-annually');
        const billingTerms = document.querySelectorAll('.billing-term');
        const lblMonthly = document.getElementById('lblMonthly');
        const lblAnnually = document.getElementById('lblAnnually');

        toggle.addEventListener('change', function() {
            if (this.checked) {
                // Annual
                monthlyPrices.forEach(el => el.classList.add('d-none'));
                annualPrices.forEach(el => el.classList.remove('d-none'));
                billingTerms.forEach(el => el.innerHTML = '/{{ __('yr') }}');
                lblMonthly.classList.add('text-muted');
                lblAnnually.classList.remove('text-muted');
            } else {
                // Monthly
                monthlyPrices.forEach(el => el.classList.remove('d-none'));
                annualPrices.forEach(el => el.classList.add('d-none'));
                billingTerms.forEach(el => el.innerHTML = '/{{ __('mo') }}');
                lblMonthly.classList.remove('text-muted');
                lblAnnually.classList.add('text-muted');
            }
        });
    });
</script>
@endpush
@endsection
