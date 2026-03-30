@extends('platform.layout')

@section('title', __('Change password'))

@section('content')
<div class="mx-auto max-w-lg">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight text-center">
                {{ __('Change Password') }}
            </h2>
        </div>
    </div>

    <div class="bg-white shadow ring-1 ring-gray-900/5 sm:rounded-xl">
        <form method="POST" action="{{ route('platform.password.update') }}" class="px-4 py-6 sm:p-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Current Password') }}</label>
                    <div class="mt-2 text-primary-900">
                        <input type="password" name="current_password" id="current_password" required autocomplete="current-password"
                            class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 @error('current_password') ring-red-300 @enderror">
                    </div>
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">{{ __('New Password') }}</label>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" required autocomplete="new-password"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 @error('password') ring-red-300 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Confirm Password') }}</label>
                    <div class="mt-2 text-primary-900">
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                            class="block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 italic text-xs text-gray-500">
                <i class="bi bi-info-circle me-1"></i> {{ __('Ensure your password is long and complex for better security.') }}
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('platform.dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">{{ __('Cancel') }}</a>
                <button type="submit"
                    class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                    {{ __('Update password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
