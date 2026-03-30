@extends('layouts.tenant')

@section('title', __('Reset Password'))

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
            <div class="text-center mb-8">
                <i class="bi bi-key text-primary-600 text-4xl"></i>
                <h2 class="mt-4 text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Set New Password') }}
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    {{ __('Please enter your new password below.') }}
                </p>
            </div>

            <div id="reset-error" class="hidden mb-4 rounded-md bg-red-50 p-4 ring-1 ring-red-600/20">
                <p class="text-sm font-medium text-red-800"></p>
            </div>
            
            <div id="reset-success" class="hidden mb-4 rounded-md bg-green-50 p-4 ring-1 ring-green-600/20">
                <p class="text-sm font-medium text-green-800"></p>
                <div class="mt-2">
                    <a href="{{ route('tenant.login') }}" class="text-sm font-bold text-green-800 underline">
                        {{ __('Go to Login') }}
                    </a>
                </div>
            </div>

            <form id="reset-form" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('Email address') }}
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" value="{{ $email }}" readonly required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('New Password') }}
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        {{ __('Confirm New Password') }}
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" id="submit-btn"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('reset-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = document.getElementById('submit-btn');
    const errorDiv = document.getElementById('reset-error');
    const successDiv = document.getElementById('reset-success');
    
    errorDiv.classList.add('hidden');
    successDiv.classList.add('hidden');
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('/api/auth/reset-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                token: form.token.value,
                email: form.email.value,
                password: form.password.value,
                password_confirmation: form.password_confirmation.value
            })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            successDiv.querySelector('p').textContent = data.message;
            successDiv.classList.remove('hidden');
            form.classList.add('hidden');
        } else {
            errorDiv.querySelector('p').textContent = data.message || '{{ __("Failed to reset password. Please check the link or try again.") }}';
            errorDiv.classList.remove('hidden');
        }
    } catch (err) {
        errorDiv.querySelector('p').textContent = '{{ __("A network error occurred.") }}';
        errorDiv.classList.remove('hidden');
    } finally {
        submitBtn.disabled = false;
    }
});
</script>
@endpush
@endsection
