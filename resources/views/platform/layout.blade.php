<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Platform')) — {{ config('app.name') }}</title>
    
    <!-- Local Assets (Fix for CSP) -->
    <script src="{{ asset('vendor/tailwind/tailwind.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                    },
                },
            },
        }
    </script>

    <!-- Alpine.js (Local) -->
    <script defer src="{{ asset('vendor/alpine/alpine.js') }}"></script>
    
    <!-- Bootstrap Icons (Local) -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}">

    <style type="text/tailwindcss">
        @layer components {
            .nav-link {
                @apply flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150;
            }
            .nav-link-active {
                @apply bg-primary-800 text-white nav-link;
            }
            .nav-link-inactive {
                @apply text-primary-300 hover:bg-primary-700 hover:text-white nav-link;
            }
        }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: false, userDropdownOpen: false, notificationsOpen: false }">
    <div>
        <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
        <div class="relative z-50 lg:hidden" x-show="sidebarOpen" x-cloak>
            <div class="fixed inset-0 bg-gray-900/80" x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            <div class="fixed inset-0 flex">
                <div class="relative mr-16 flex w-full max-w-xs flex-1" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <i class="bi bi-x-lg text-white text-2xl"></i>
                        </button>
                    </div>

                    <!-- Sidebar component for mobile -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-primary-900 px-6 pb-4 ring-1 ring-white/10">
                        <div class="flex h-16 shrink-0 items-center">
                            <span class="text-white font-bold text-xl">{{ config('app.name') }}</span>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                <li>
                                    <ul role="list" class="-mx-2 space-y-1">
                                        <li>
                                            <a href="{{ route('platform.dashboard') }}" class="{{ request()->routeIs('platform.dashboard') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                                <i class="bi bi-grid-fill me-3"></i>
                                                {{ __('Dashboard') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('platform.tenants.create') }}" class="{{ request()->routeIs('platform.tenants.create') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                                <i class="bi bi-plus-circle-fill me-3"></i>
                                                {{ __('Provision Tenant') }}
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-primary-900 px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center">
                    <span class="text-white font-bold text-2xl tracking-tight">{{ config('app.name') }}</span>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-1">
                                <li>
                                    <a href="{{ route('platform.dashboard') }}" class="{{ request()->is('platform') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                        <i class="bi bi-speedometer2 me-3"></i>
                                        {{ __('Dashboard') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('platform.tenants.index') }}" class="{{ request()->is('platform/tenants*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                        <i class="bi bi-building me-3"></i>
                                        {{ __('Tenants') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('ilsawn.index') }}" class="{{ request()->is('ilsawn*') ? 'nav-link-active' : 'nav-link-inactive' }}">
                                        <i class="bi bi-translate me-3"></i>
                                        {{ __('Translations') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="lg:pl-64">
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <i class="bi bi-list text-2xl"></i>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="relative flex flex-1"></div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications -->
                        <div class="relative">
                            <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500" @click="notificationsOpen = !notificationsOpen">
                                <span class="sr-only">View notifications</span>
                                <i class="bi bi-bell text-xl"></i>
                            </button>
                            <div x-show="notificationsOpen" @click.away="notificationsOpen = false" x-cloak class="absolute right-0 z-10 mt-2.5 w-80 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                <div class="px-4 py-2 text-sm font-semibold text-gray-900 border-b border-gray-100">{{ __('Notifications') }}</div>
                                <div class="px-4 py-6 text-sm text-center text-gray-500">{{ __('No new notifications') }}</div>
                            </div>
                        </div>

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"></div>

                        <!-- User profile dropdown -->
                        <div class="relative">
                            <button type="button" class="-m-1.5 flex items-center p-1.5" @click="userDropdownOpen = !userDropdownOpen">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                    {{ substr(Auth::user()->email, 0, 1) }}
                                </div>
                                <span class="hidden lg:flex lg:items-center">
                                    <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">{{ Auth::user()->email }}</span>
                                    <i class="bi bi-chevron-down ml-2 text-gray-400 text-xs"></i>
                                </span>
                            </button>

                            <div x-show="userDropdownOpen" @click.away="userDropdownOpen = false" x-cloak class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                                <a href="{{ route('platform.password.edit') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">{{ __('Change Password') }}</a>
                                
                                @if(!Auth::user()->hasEnabledTwoFactorAuthentication())
                                    <a href="{{ route('platform.two-factor.setup') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50 text-yellow-600 font-medium italic">
                                        <i class="bi bi-shield-lock me-1"></i> {{ __('Enable 2FA') }}
                                    </a>
                                @endif

                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('platform.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                                        {{ __('Sign out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @if (session('status'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 ring-1 ring-green-600/20">
                            <div class="flex">
                                <div class="flex-shrink-0 text-green-400">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4 ring-1 ring-red-600/20">
                            <div class="flex">
                                <div class="flex-shrink-0 text-red-400">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
