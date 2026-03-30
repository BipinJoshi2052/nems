@extends('layouts.public')

@section('title', __('Platform login'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">{{ __('Platform admin login') }}</h1>
                    <form method="POST" action="{{ route('platform.login.attempt') }}" class="needs-validation">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">{{ __('Password') }}</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                            <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">{{ __('Sign in') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
