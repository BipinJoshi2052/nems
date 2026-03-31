<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-montessori-cream">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Institution')) — {{ config('app.name') }}</title>
    
    <!-- Local Assets -->
    <script src="/vendor/tailwind/tailwind.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef8f3',
                            100: '#fdeee1',
                            200: '#fad9bc',
                            300: '#f7be8d',
                            400: '#f39a5c',
                            500: '#ef7f3a',
                            600: '#e0632f',
                            700: '#ba4c28',
                            800: '#943e27',
                            900: '#783522',
                            950: '#411910',
                        },
                        montessori: {
                            green: '#8bb174',
                            blue: '#6ba3bf',
                            yellow: '#f4d35e',
                            pink: '#ee8695',
                            cream: '#faf4ed',
                        },
                    },
                    fontFamily: {
                        'display': ['Georgia', 'serif'],
                    },
                },
            },
        }
    </script>

    <!-- Alpine.js (Local) -->
    <script defer src="/vendor/alpine/alpine.js"></script>

    <!-- Bootstrap Icons (Local) -->
    <link rel="stylesheet" href="/vendor/bootstrap-icons/bootstrap-icons.css">
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center space-x-3">
                            <div class="relative">
                                <div class="w-12 h-12 bg-gradient-to-br from-montessori-green to-montessori-blue rounded-full flex items-center justify-center shadow-md">
                                    <i class="bi bi-stars text-white text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-display font-bold text-gray-900">{{ tenant('name') ?? config('app.name') }}</h1>
                                <p class="text-xs text-gray-500 italic">Nurturing young minds</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="#about" class="text-gray-700 hover:text-primary-600 transition font-medium">About</a>
                        <a href="#programs" class="text-gray-700 hover:text-primary-600 transition font-medium">Programs</a>
                        <a href="#contact" class="text-gray-700 hover:text-primary-600 transition font-medium">Contact</a>
                        <a href="{{ route('tenant.login') }}" class="text-gray-700 hover:text-white transition font-medium bg-primary-600 text-white px-4 py-2 rounded-md">Log In</a>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            @if (session('status'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    <div class="rounded-lg bg-green-50 p-4 border-l-4 border-montessori-green">
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    <div class="rounded-lg bg-red-50 p-4 border-l-4 border-red-500">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-display font-bold mb-4">{{ tenant('name') ?? config('app.name') }}</h3>
                        <p class="text-gray-400 text-sm">Inspiring independence, creativity, and a love for learning through the Montessori method.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold mb-4 text-montessori-yellow">Quick Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Admissions</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Curriculum</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition">Parent Resources</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold mb-4 text-montessori-yellow">Contact Us</h4>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li class="flex items-center"><i class="bi bi-telephone me-2"></i> (555) 123-4567</li>
                            <li class="flex items-center"><i class="bi bi-envelope me-2"></i> info@montessori.edu</li>
                            <li class="flex items-center"><i class="bi bi-geo-alt me-2"></i> 123 Learning Lane</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ tenant('name') ?? config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
