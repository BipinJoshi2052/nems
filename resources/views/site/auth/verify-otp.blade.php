@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock-fill text-success" style="font-size: 3rem;"></i>
                <h2 class="fw-bold mt-2">{{ __('Verify Your Email') }}</h2>
                <p class="text-muted">{{ __('We sent a 6-digit code to') }} <strong>{{ session('verify_email') }}</strong></p>
            </div>

            @if(session('status'))
                <div class="alert alert-info text-center">{{ session('status') }}</div>
            @endif

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('site.verify-otp.submit') }}">
                        @csrf
                        
                        <div class="mb-4 text-center">
                            <label for="otp" class="form-label fw-bold">{{ __('Enter Optional Code') }}</label>
                            <input type="text" class="form-control form-control-lg text-center fw-bold letter-spacing-lg @error('otp') is-invalid @enderror" 
                                id="otp" name="otp" maxlength="6" autocomplete="one-time-code" required autofocus>
                            @error('otp')
                                <div class="invalid-feedback fw-normal">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold">{{ __('Verify OTP') }}</button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted small mb-1">{{ __('Didn\'t receive the code?') }}</p>
                        <!-- Resend logic is typically another route, but disabled via JS initially -->
                        <button id="resendBtn" class="btn btn-sm btn-outline-secondary" disabled>
                            {{ __('Resend Code in') }} <span id="countdown">60</span>s
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .letter-spacing-lg { letter-spacing: 0.5rem; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let timeLeft = 60;
        const resendBtn = document.getElementById('resendBtn');
        const countdownSpan = document.getElementById('countdown');

        const timer = setInterval(() => {
            timeLeft--;
            countdownSpan.innerText = timeLeft;
            if(timeLeft <= 0) {
                clearInterval(timer);
                resendBtn.removeAttribute('disabled');
                resendBtn.innerHTML = '{{ __('Resend Code') }}';
            }
        }, 1000);
        
        // Form manipulation to restrict non-numbers
        const otpInput = document.getElementById('otp');
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
@endpush
@endsection
