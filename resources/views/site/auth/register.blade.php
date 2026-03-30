@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
                <h2 class="fw-bold mt-2">{{ __('Start Your Journey with EduNepal') }}</h2>
                <p class="text-muted">{{ __('Fill out the details below to set up your institution.') }}</p>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('site.register.submit') }}">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $selectedPlan }}">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">{{ __('Institution Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">{{ __('Phone Number') }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subdomain" class="form-label fw-bold">{{ __('Subdomain') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('subdomain') is-invalid @enderror" id="subdomain" name="subdomain" value="{{ old('subdomain') }}" required placeholder="your-institution">
                                <span class="input-group-text">.{{ config('tenancy.central_domains')[0] }}</span>
                            </div>
                            <div id="subdomain-feedback" class="small mt-1"></div>
                            @error('subdomain')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="vertical" class="form-label fw-bold">{{ __('Institution Type (Vertical)') }}</label>
                            <select class="form-select @error('vertical') is-invalid @enderror" id="vertical" name="vertical" required>
                                <option value="" disabled selected>{{ __('Select an institution type...') }}</option>
                                <option value="montessori">{{ __('Montessori') }}</option>
                                <option value="school" disabled>{{ __('School') }} {{ __('(Coming Soon)') }}</option>
                            </select>
                            @error('vertical')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" id="submit-btn" class="btn btn-primary w-100 py-2 fw-bold">{{ __('Continue') }}</button>
                    </form>
                </div>
            </div>

            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const subdomainInput = document.getElementById('subdomain');
                    const feedback = document.getElementById('subdomain-feedback');
                    const submitBtn = document.getElementById('submit-btn');
                    const form = submitBtn.closest('form');
                    let timeout = null;

                    subdomainInput.addEventListener('input', function() {
                        clearTimeout(timeout);
                        const subdomain = this.value.trim().toLowerCase();
                        
                        // Sanitize input
                        this.value = subdomain.replace(/[^a-z0-z0-9-]/g, '');

                        if (subdomain.length < 3) {
                            feedback.innerHTML = '';
                            return;
                        }

                        feedback.innerHTML = '<span class="text-muted"><i class="bi bi-hourglass-split"></i> Checking availability...</span>';

                        timeout = setTimeout(() => {
                            fetch(`{{ route('api.check-subdomain') }}?subdomain=${subdomain}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.available) {
                                        feedback.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Subdomain is available!</span>';
                                    } else {
                                        feedback.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle-fill"></i> Subdomain is already taken.</span>';
                                    }
                                })
                                .catch(err => {
                                    console.error('Error checking subdomain:', err);
                                    feedback.innerHTML = '';
                                });
                        }, 1000);
                    });

                    form.addEventListener('submit', function() {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                    });
                });
            </script>
            @endpush
            <div class="text-center mt-4">
                <p class="text-muted">{{ __('Already have an account?') }} <a href="{{ route('platform.login') }}" class="text-decoration-none">{{ __('Log in here') }}</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
