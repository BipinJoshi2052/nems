@extends('platform.layout')

@section('title', __('Dashboard'))

@section('content')
<div class="sm:flex sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            {{ __('Welcome back, Admin') }}
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Signed in as :email', ['email' => auth('platform')->user()->email]) }}
        </p>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Quick Stats or Info -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-900/5 transition hover:shadow-md">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                        <i class="bi bi-shield-lock text-2xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">{{ __('Security') }}</dt>
                        <dd>
                            <div class="text-lg font-semibold text-gray-900">
                                @if(auth('platform')->user()->hasEnabledTwoFactorAuthentication())
                                    <span class="text-green-600 text-sm font-normal flex items-center">
                                        <i class="bi bi-check-circle-fill me-1"></i> {{ __('2FA Enabled') }}
                                    </span>
                                @else
                                    <span class="text-yellow-600 text-sm font-normal flex items-center">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ __('2FA Disabled') }}
                                    </span>
                                @endif
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3">
            <div class="text-sm">
                <a href="{{ route('platform.password.edit') }}" class="font-medium text-primary-600 hover:text-primary-500 flex items-center">
                    {{ __('Manage security') }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tenant Management Quick Link -->
    <div class="overflow-hidden rounded-lg bg-white shadow ring-1 ring-gray-900/5 transition hover:shadow-md">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary-100 text-primary-600">
                        <i class="bi bi-building-add text-2xl"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500">{{ __('Tenants') }}</dt>
                        <dd>
                            <div class="text-lg font-semibold text-gray-900">{{ __('Onboarding') }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3">
            <div class="text-sm">
                <a href="{{ route('platform.tenants.create') }}" class="font-medium text-primary-600 hover:text-primary-500 flex items-center">
                    {{ __('Provision new tenant') }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="rounded-lg bg-blue-50 p-6 border border-blue-100">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="bi bi-info-circle-fill text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">{{ __('Platform Updates') }}</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>{{ __('You are currently running the NEMS Platform v1.0. All systems are operational.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
