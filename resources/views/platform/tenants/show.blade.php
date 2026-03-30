@extends('platform.layout')

@section('title', __('Tenant Details'))

@section('content')
<div class="md:flex md:items-center md:justify-between mb-8">
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            {{ $tenant->name }}
        </h2>
        <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
            <div class="mt-2 flex items-center text-sm text-gray-500 italic">
                <i class="bi bi-link-45deg me-1 flex-shrink-0 text-gray-400"></i>
                {{ $tenant->subdomain }}.{{ config('tenancy.central_domains')[0] }}
            </div>
            <div class="mt-2 flex items-center text-sm text-gray-500">
                <i class="bi bi-clock me-1 flex-shrink-0 text-gray-400"></i>
                {{ __('Created :relative', ['relative' => $tenant->created_at->diffForHumans()]) }}
            </div>
        </div>
    </div>
    <div class="mt-4 flex md:ml-4 md:mt-0 gap-x-3">
        <a href="{{ route('platform.tenants.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
        </a>
        
        <form action="{{ route('platform.tenants.toggle-status', $tenant->id) }}" method="POST">
            @csrf
            @method('PATCH')
            @if($tenant->status === 'active')
                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    <i class="bi bi-pause-fill me-1"></i> {{ __('Suspend') }}
                </button>
            @elseif($tenant->status === 'suspended')
                <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                    <i class="bi bi-play-fill me-1"></i> {{ __('Activate') }}
                </button>
            @endif
        </form>

        <button type="button" disabled class="cursor-not-allowed inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition opacity-50">
            <i class="bi bi-pencil-square me-1"></i> {{ __('Edit') }}
        </button>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white shadow ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden hover:shadow-md transition">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-sm font-semibold leading-6 text-gray-900 uppercase tracking-wider">{{ __('Institution Overview') }}</h3>
                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $tenant->status === 'active' ? 'bg-green-50 text-green-700 ring-green-600/20' : ($tenant->status === 'pending' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 'bg-red-50 text-red-700 ring-red-600/20') }}">
                    {{ ucfirst($tenant->status) }}
                </span>
            </div>
            <div class="border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-900">{{ __('Administrator Email') }}</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 font-medium italic">{{ $tenant->email }}</dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-900">{{ __('Vertical') }}</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 uppercase tracking-tight">{{ $tenant->vertical }}</dd>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-900">{{ __('Database ID') }}</dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0 font-mono text-xs">{{ $tenant->id }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Sidebar Stats/Actions -->
    <div class="space-y-6">
        <div class="bg-white shadow ring-1 ring-gray-900/5 sm:rounded-xl p-6">
            <h3 class="text-sm font-semibold leading-6 text-gray-900 uppercase tracking-wider mb-4">{{ __('Quick Actions') }}</h3>
            <div class="space-y-3">
                <button disabled title="Implementation Pending" class="w-full inline-flex items-center justify-center rounded-md bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-400 shadow-sm ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                    <i class="bi bi-envelope me-2"></i> {{ __('Resend Invite') }}
                </button>
                <button disabled title="Implementation Pending" class="w-full inline-flex items-center justify-center rounded-md bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-400 shadow-sm ring-1 ring-inset ring-gray-300 cursor-not-allowed">
                    <i class="bi bi-shield-lock me-2"></i> {{ __('Reset Passwords') }}
                </button>
            </div>
        </div>

        <div class="rounded-lg bg-yellow-50 p-6 border border-yellow-100">
            <div class="flex">
                <div class="flex-shrink-0 text-yellow-400">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">{{ __('Important Note') }}</h3>
                    <div class="mt-2 text-xs text-yellow-700">
                        <p>{{ __('Suspending a tenant will immediately block access for all its users and staff members until activated again.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
