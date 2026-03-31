@extends('tenant-website.layouts.tenant')

@section('title', __('Institution Login'))

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-montessori-cream" x-data="{ showForgot: false, forgotEmail: '', loading: false, message: '', error: '' }">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-primary-100 rounded-full flex items-center justify-center shadow-inner">
                    <i class="bi bi-shield-lock text-primary-600 text-3xl"></i>
                </div>
                <h2 class="text-3xl font-display font-bold text-gray-900">
                    {{ tenant('name') }}
                </h2>
                <p class="mt-2 text-sm text-gray-500 font-medium tracking-wide italic">
                    {{ __('Sign in to access your portal') }}
                </p>
            </div>

            <div id="login-error" class="hidden mb-4 rounded-md bg-red-50 p-4 ring-1 ring-red-600/20">
                <p class="text-sm font-medium text-red-800"></p>
            </div>

            <form id="login-form" class="space-y-6" @submit.prevent="handleLogin">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('Email address') }}
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('Password') }}
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" 
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    <div class="text-sm">
                        <button type="button" @click="showForgot = true" class="font-medium text-primary-600 hover:text-primary-500">
                            {{ __('Forgot your password?') }}
                        </button>
                    </div>
                </div>

                <div>
                    <button type="submit" id="submit-btn"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                        {{ __('Sign in') }}
                    </button>
                </div>
            </form>

            <!-- Forgot Password Modal -->
            <template x-if="showForgot">
                <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showForgot = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 shadow-2xl border border-gray-200">
                            <div>
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100">
                                    <i class="bi bi-envelope text-primary-600 text-xl"></i>
                                </div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ __('Reset Password') }}</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">{{ __('Enter your email address and we will send you a link to reset your password.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 space-y-4">
                                <input type="email" x-model="forgotEmail" placeholder="Email address" 
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                
                                <div x-show="message" class="text-sm text-green-600 font-medium" x-text="message"></div>
                                <div x-show="error" class="text-sm text-red-600 font-medium" x-text="error"></div>

                                <button type="button" @click="
                                    loading = true; message = ''; error = '';
                                    fetch('/api/auth/forgot-password', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                        body: JSON.stringify({ email: forgotEmail })
                                    }).then(res => res.json()).then(data => {
                                        message = data.message;
                                    }).catch(err => {
                                        error = 'Failed to send reset link.';
                                    }).finally(() => { loading = false })
                                " :disabled="loading || !forgotEmail"
                                class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm disabled:opacity-50">
                                    <span x-show="!loading">{{ __('Send Reset Link') }}</span>
                                    <span x-show="loading"><i class="bi bi-arrow-repeat animate-spin me-2"></i> {{ __('Sending...') }}</span>
                                </button>
                                <button type="button" @click="showForgot = false" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm">
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleLogin(e) {
    const form = e.target;
    const submitBtn = document.getElementById('submit-btn');
    const errorDiv = document.getElementById('login-error');
    const errorMsg = errorDiv.querySelector('p');
    
    errorDiv.classList.add('hidden');
    submitBtn.disabled = true;
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin me-2"></i> {{ __("Signing in...") }}';
    
    fetch('/api/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            email: form.email.value,
            password: form.password.value
        })
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (ok) {
            localStorage.setItem('tenant_token', data.access_token);
            localStorage.setItem('tenant_user', JSON.stringify(data.user));
            window.location.href = data.redirect;
        } else {
            errorMsg.textContent = data.message || '{{ __("Login failed. Please check your credentials.") }}';
            errorDiv.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalContent;
        }
    })
    .catch(err => {
        errorMsg.textContent = '{{ __("A network error occurred. Please try again.") }}';
        errorDiv.classList.remove('hidden');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalContent;
    });
}
</script>
@endpush
@endsection
