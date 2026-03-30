@extends('platform.layout')

@section('title', __('Create Tenant'))

@section('content')
<div class="md:flex md:items-center md:justify-between mb-8">
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
            {{ __('Invite New Tenant') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Define the institution details and subscription plan to send a setup invitation.') }}
        </p>
    </div>
    <div class="mt-4 flex md:ml-4 md:mt-0">
        <a href="{{ route('platform.tenants.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
            <i class="bi bi-arrow-left me-1"></i> {{ __('Back to List') }}
        </a>
    </div>
</div>

<div class="mx-auto max-w-2xl">
    <div class="bg-white shadow ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
        <form method="POST" action="{{ route('platform.tenants.store') }}" class="px-4 py-6 sm:p-8">
            @csrf

            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Institution Name -->
                <div class="sm:col-span-4">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Institution Name') }}</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 @error('name') ring-red-300 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Owner Email -->
                <div class="sm:col-span-4">
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Owner Email') }}</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 @error('email') ring-red-300 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subdomain -->
                <div class="sm:col-span-3">
                    <label for="subdomain" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Desired Subdomain') }}</label>
                    <div class="mt-2">
                        <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 sm:max-w-md">
                            <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" required
                                class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" placeholder="school-name">
                            {{-- <span class="flex select-none items-center pr-3 text-gray-500 sm:text-sm">.{{ config('tenancy.central_domains')[0] }}</span> --}}
                            <span class="flex select-none items-center pr-3 text-gray-500 sm:text-sm">.{{ config('tenancy.central_domains')[0] }}</span>
                        </div>
                    </div>
                    <div id="subdomain-feedback" class="mt-2 text-xs"></div>
                    @error('subdomain')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vertical -->
                <div class="sm:col-span-3">
                    <label for="vertical" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Vertical') }}</label>
                    <div class="mt-2">
                        <select id="vertical" name="vertical" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:max-w-xs sm:text-sm sm:leading-6 @error('vertical') ring-red-300 @enderror">
                            <option value="" disabled selected>{{ __('Select Vertical') }}</option>
                            <option value="montessori">{{ __('Montessori') }}</option>
                        </select>
                    </div>
                    @error('vertical')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subscription Plan -->
                <div class="sm:col-span-3">
                    <label for="plan_id" class="block text-sm font-medium leading-6 text-gray-900">{{ __('Subscription Plan') }}</label>
                    <div class="mt-2">
                        <select id="plan_id" name="plan_id" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:max-w-xs sm:text-sm sm:leading-6 @error('plan_id') ring-red-300 @enderror">
                            <option value="" disabled selected>{{ __('Select Plan') }}</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} ({{ ucfirst($plan->vertical) }})</option>
                            @endforeach
                        </select>
                    </div>
                    @error('plan_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-6 border-t border-gray-900/10 pt-6">
                <a href="{{ route('platform.tenants.index') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">{{ __('Cancel') }}</a>
                <button type="submit"
                    class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                    {{ __('Send Setup Invitation') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subdomainInput = document.getElementById('subdomain');
        const feedback = document.getElementById('subdomain-feedback');
        const submitBtn = document.querySelector('button[type="submit"]');
        let timeout = null;

        subdomainInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const subdomain = this.value.trim().toLowerCase();
            
            // Sanitize: lowercase, numbers, hyphens only
            this.value = subdomain.replace(/[^a-z0-9-]/g, '');

            if (this.value.length < 3) {
                feedback.innerHTML = '';
                return;
            }

            feedback.innerHTML = '<span class="text-gray-500 italic flex items-center"><i class="bi bi-hourglass-split me-1 animate-spin"></i> Checking availability...</span>';

            timeout = setTimeout(() => {
                fetch(`{{ route('api.check-subdomain') }}?subdomain=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            feedback.innerHTML = '<span class="text-green-600 font-medium flex items-center"><i class="bi bi-check-circle-fill me-1"></i> Subdomain is available!</span>';
                        } else {
                            feedback.innerHTML = '<span class="text-red-600 font-medium flex items-center"><i class="bi bi-x-circle-fill me-1"></i> Subdomain is already taken.</span>';
                        }
                    })
                    .catch(err => {
                        console.error('Error checking subdomain:', err);
                        feedback.innerHTML = '';
                    });
            }, 800);
        });
    });
</script>
@endpush
