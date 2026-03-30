@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <span class="badge bg-success">{{ __('Active User') }}</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <h2 class="fw-bold mb-4">{{ __('My Account & Subscription') }}</h2>

            @if($tenant)
                <!-- Tenant / Subdomain Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-building me-2 text-primary"></i>{{ __('Institution Details') }}</h5>
                        <span class="badge bg-success">{{ ucfirst($tenant->status) }}</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">{{ __('Institution Name') }}</div>
                            <div class="col-sm-9 fw-bold">{{ $tenant->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-3 text-muted">{{ __('Subdomain') }}</div>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="tenantDomain" value="{{ $domain->domain ?? 'Not assigned yet' }}" readonly>
                                    @if($domain)
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyDomain()">
                                        <i class="bi bi-clipboard"></i> {{ __('Copy') }}
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-muted">{{ __('Access Portal') }}</div>
                            <div class="col-sm-9">
                                @if($domain)
                                <a href="http://{{ $domain->domain }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center">
                                    {{ __('Go to Portal') }} <i class="bi bi-box-arrow-up-right ms-2"></i>
                                </a>
                                @else
                                <button class="btn btn-secondary disabled">{{ __('Provisioning...') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2 text-primary"></i>{{ __('Subscription') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        @if($subscription)
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="fw-bold text-success mb-1">{{ $subscription->plan_name }}</h4>
                                <p class="text-muted mb-0">{{ __('Billed monthly') }}</p>
                            </div>
                            <h3 class="fw-bold mb-0">रु {{ number_format($subscription->price_monthly ?? 0) }}<span class="fs-6 text-muted fw-normal">/{{ __('mo') }}</span></h3>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between text-muted align-items-center mt-4">
                            <span>
                                @if($subscription->status === 'trial')
                                    {{ __('Trial ends on') }}: {{ \Carbon\Carbon::parse($subscription->trial_ends_at)->format('M d, Y') }}
                                @else
                                    {{ __('Next billing date') }}: {{ \Carbon\Carbon::parse($subscription->expires_at)->format('M d, Y') }}
                                @endif
                            </span>
                            <button class="btn btn-outline-danger btn-sm">{{ __('Cancel Subscription') }}</button>
                        </div>
                        @else
                        <p class="text-muted">{{ __('No active subscription found.') }}</p>
                        <a href="{{ route('pricing') }}" class="btn btn-sm btn-primary">{{ __('Subscribe Now') }}</a>
                        @endif
                    </div>
                </div>
            @else
                <!-- No Tenant Warning -->
                <div class="alert alert-warning d-flex align-items-center p-4 rounded shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div>
                        <h5 class="alert-heading fw-bold mb-1">{{ __('No Institution Found') }}</h5>
                        <p class="mb-0">{{ __('We could not find an active institution or subscription tied to your account.') }}</p>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('pricing') }}" class="btn btn-primary btn-lg">{{ __('View Pricing and Register') }}</a>
                </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyDomain() {
        var copyText = document.getElementById("tenantDomain");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText("http://" + copyText.value);
        
        // Simple toast or feedback
        let btn = copyText.nextElementSibling;
        let originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> {{ __('Copied!') }}';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    }
</script>
@endpush
@endsection
