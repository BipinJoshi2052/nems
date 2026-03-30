@extends('platform.layout')

@section('title', __('Two-factor challenge'))

@section('content')
<div class="mx-auto max-w-md">
    <div class="text-center mb-8">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary-100">
            <i class="bi bi-shield-lock-fill text-2xl text-primary-600"></i>
        </div>
        <h2 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">{{ __('Security Verification') }}</h2>
        <p class="mt-2 text-sm text-gray-600">{{ __('Please enter the code from your authenticator app to continue.') }}</p>
    </div>

    <div class="bg-white shadow ring-1 ring-gray-900/5 sm:rounded-xl">
        <form method="POST" action="{{ route('platform.two-factor.verify') }}" class="px-6 py-8">
            @csrf
            
            <div>
                <label for="code" class="block text-sm font-medium leading-6 text-gray-900">{{ __('6-Digit Code') }}</label>
                <div class="mt-2">
                    <input type="text" name="code" id="code" required autocomplete="one-time-code" autofocus
                        placeholder="000000"
                        class="block w-full rounded-md border-0 py-2.5 text-center text-xl font-bold tracking-[0.5em] text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 @error('code') ring-red-300 @enderror">
                </div>
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8">
                <button type="submit"
                    class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                    {{ __('Verify and Sign In') }}
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 text-center">
        <form method="POST" action="{{ route('platform.logout') }}">
            @csrf
            <button type="submit" class="text-xs text-gray-500 hover:text-gray-700 underline underline-offset-4">
                {{ __('Cancel and return to login') }}
            </button>
        </form>
    </div>
</div>
@endsection
