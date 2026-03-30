@extends('platform.layout')

@section('title', __('Tenants'))

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            {{ __('Tenants') }}
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Manage all institution instances, their subdomains, and subscription statuses.') }}
        </p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('platform.tenants.create') }}" 
           class="block rounded-md bg-primary-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition">
            <i class="bi bi-plus-lg me-1"></i> {{ __('New Tenant') }}
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ __('Institution') }}</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Subdomain') }}</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Plan') }}</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Status') }}</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">{{ __('View') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($tenants as $tenant)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                                {{ substr($tenant->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $tenant->name }}</div>
                                            <div class="text-gray-500 italic text-xs">{{ $tenant->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 tracking-tight">
                                        {{ $tenant->subdomain }}.{{ config('tenancy.central_domains')[0] }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 italic">
                                    {{ ucfirst($tenant->vertical) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                    @php
                                        $statusClasses = [
                                            'active' => 'bg-green-50 text-green-700 ring-green-600/20',
                                            'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                            'suspended' => 'bg-red-50 text-red-700 ring-red-600/20',
                                        ];
                                        $class = $statusClasses[$tenant->status] ?? $statusClasses['pending'];
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $class }}">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('platform.tenants.show', $tenant->id) }}" class="text-primary-600 hover:text-primary-900 flex items-center justify-end">
                                        {{ __('View Details') }} <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-gray-500 italic font-medium">
                                    {{ __('No tenants found. Invite your first tenant to get started.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
