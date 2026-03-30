@extends('layouts.public')

@section('title', __('Forgot Password'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 60px; height: 60px;">
                            <i class="bi bi-envelope-open text-primary fs-3"></i>
                        </div>
                        <h1 class="h4 fw-bold">{{ __('Forgot Password?') }}</h1>
                        <p class="text-muted small">{{ __('Enter your email address and we will send you a link to reset your password.') }}</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('platform.password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold small text-uppercase tracking-wider">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">
                            {{ __('Send Reset Link') }}
                        </button>
                        
                        <div class="text-center">
                            <a href="{{ route('platform.login') }}" class="text-decoration-none small fw-bold">
                                <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Login') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
