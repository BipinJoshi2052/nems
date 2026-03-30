@extends('platform.layout')

@section('title', __('Two-factor setup'))

@section('content')
<div class="mx-auto max-w-lg">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1 text-center">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ __('Secure Your Account') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                {{ __('Enable two-factor authentication to add an extra layer of security.') }}
            </p>
        </div>
    </div>

    <div class="bg-white shadow ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
        <div class="p-8">
            <div class="flex flex-col items-center">
                <div class="mb-6 rounded-lg bg-gray-50 p-4 ring-1 ring-gray-200">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrUrl) }}" 
                         alt="QR Code" class="h-48 w-48">
                </div>
                
                <div class="text-center mb-8">
                    <p class="text-sm text-gray-600 mb-2">{{ __('Scan this QR code with your authenticator app.') }}</p>
                    <div class="inline-flex items-center rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-700/10 uppercase tracking-wider">
                        {{ __('Secret Key') }}: {{ $secret }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('platform.two-factor.setup.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="code" class="block text-sm font-medium leading-6 text-gray-900">{{ __('6-Digit Confirmation Code') }}</label>
                    <div class="mt-2">
                        <input type="text" name="code" id="code" required autocomplete="one-time-code" autofocus
                            placeholder="000000"
                            class="block w-full rounded-md border-0 py-2 text-center text-lg font-bold tracking-[0.5em] text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 @error('code') ring-red-300 @enderror">
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-x-4">
                    <a href="{{ route('platform.dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">{{ __('Cancel') }}</a>
                    <button type="submit"
                        class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                        {{ __('Verify and Enable') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 italic text-xs text-gray-500 flex items-center">
            <i class="bi bi-info-circle me-2"></i> {{ __('Make sure you have backed up your recovery codes if applicable.') }}
        </div>
    </div>
</div>
@endsection
