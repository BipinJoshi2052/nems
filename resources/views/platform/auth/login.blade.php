@extends('layouts.public')

@section('title', __('Platform login'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-person-badge text-primary fs-2"></i>
                        </div>
                        <h1 class="h4 fw-bold">{{ __('Login') }}</h1>
                        {{-- <p class="text-muted small">{{ __('Sign in to manage your education platform') }}</p> --}}
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('platform.login.attempt') }}" class="needs-validation">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase tracking-wider" for="email">{{ __('Email Address') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required autofocus autocomplete="username" placeholder="name@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-semibold small text-uppercase tracking-wider" for="password">{{ __('Password') }}</label>
                                @if (Route::has('platform.password.request'))
                                    <a class="small text-decoration-none fw-bold" href="{{ route('platform.password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <input type="password" name="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                            <label class="form-check-label text-muted small" for="remember">{{ __('Keep me signed in') }}</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-4">
                            {{ __('Sign In') }}
                        </button>

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                {{ __("Not signed up yet?") }} 
                                <a href="{{ route('site.register') }}" class="fw-bold text-decoration-none ms-1">
                                    {{ __('Create an Account') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
