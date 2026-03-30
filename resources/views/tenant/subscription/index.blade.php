@extends('layouts.tenant') {{-- Assuming a tenant layout exists --}}

@section('title', __('Subscription Management'))

@section('content')
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('Subscription Overview') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('Manage your institution plan and limits.') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $tenant->status === 'active' || $tenant->status === 'trial' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($tenant->status) }}
                </span>
            </div>
            
            <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                @if($subscription)
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Current Plan') }}</dt>
                            <dd class="mt-1 text-lg font-bold text-primary-600">{{ $subscription->plan->name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Billing Cycle') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ __('Monthly') }} (NPR {{ number_format($subscription->plan->price_monthly) }})</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Expiry Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($subscription->status === 'trial')
                                    {{ $subscription->trial_ends_at->format('M d, Y') }} (Trial)
                                @else
                                    {{ $subscription->expires_at ? $subscription->expires_at->format('M d, Y') : __('Never') }}
                                @endif
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Resource Usage') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <ul class="space-y-1">
                                    <li><i class="bi bi-people me-1"></i> {{ __('Max Students') }}: {{ $subscription->plan->max_students >= 1000000 ? __('Unlimited') : $subscription->plan->max_students }}</li>
                                    <li><i class="bi bi-hdd me-1"></i> {{ __('Storage') }}: {{ $subscription->plan->storage_gb }}GB</li>
                                </ul>
                            </dd>
                        </div>
                    </dl>
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-500 italic">{{ __('No active subscription found.') }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-gray-50 px-4 py-4 sm:px-6 border-t border-gray-200">
                <div class="flex justify-end gap-x-3">
                    <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md transition hover:bg-gray-50 text-sm font-medium">
                        {{ __('View Plans') }}
                    </button>
                    <button class="bg-primary-600 text-white px-4 py-2 rounded-md transition hover:bg-primary-700 text-sm font-medium shadow-sm">
                        {{ __('Renew or Upgrade') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-info-circle-fill text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 font-medium">
                        {{ __('Coming Soon: Full subscription management and billing history will be integrated in Phase 3.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
