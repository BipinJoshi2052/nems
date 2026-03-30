@extends('layouts.public')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-key-fill text-primary" style="font-size: 3rem;"></i>
                <h2 class="fw-bold mt-2">{{ __('Setup Password') }}</h2>
                <p class="text-muted">{{ __('Create a secure password to protect your institution.') }}</p>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('site.setup-password.submit') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">{{ __('Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autofocus>
                            <div class="form-text mt-2 text-muted small">
                                <span id="req-length" class="d-block"><i class="bi bi-x-circle text-danger me-1"></i> {{ __('At least 8 characters') }}</span>
                                <span id="req-upper" class="d-block"><i class="bi bi-x-circle text-danger me-1"></i> {{ __('One uppercase letter') }}</span>
                                <span id="req-number" class="d-block"><i class="bi bi-x-circle text-danger me-1"></i> {{ __('One number') }}</span>
                                <span id="req-special" class="d-block"><i class="bi bi-x-circle text-danger me-1"></i> {{ __('One special character') }}</span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button id="submitBtn" type="submit" class="btn btn-primary w-100 py-2 fw-bold">{{ __('Complete Setup') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pwdInput = document.getElementById('password');
        const reqLength = document.getElementById('req-length');
        const reqUpper = document.getElementById('req-upper');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        function toggleReq(element, isValid) {
            const icon = element.querySelector('i');
            if (isValid) {
                icon.classList.remove('bi-x-circle', 'text-danger');
                icon.classList.add('bi-check-circle-fill', 'text-success');
            } else {
                icon.classList.remove('bi-check-circle-fill', 'text-success');
                icon.classList.add('bi-x-circle', 'text-danger');
            }
        }

        pwdInput.addEventListener('input', (e) => {
            const val = e.target.value;
            toggleReq(reqLength, val.length >= 8);
            toggleReq(reqUpper, /[A-Z]/.test(val));
            toggleReq(reqNumber, /[0-9]/.test(val));
            toggleReq(reqSpecial, /[^A-Za-z0-9]/.test(val));
        });
    });
</script>
@endpush
@endsection
