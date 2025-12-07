<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'VetCare - Sistem Manajemen Klinik Hewan')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Top navigation for authenticated users -->
        @auth
            @include('components.navigation')
        @endauth

        <!-- Hero (if provided) -->
        @hasSection('hero')
            @yield('hero')
        @endif

        <!-- Main content area (page header, flash messages, content) -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <!-- Page header -->
                    <div class="pb-5 border-b border-gray-200">
                        <h1 class="text-3xl font-bold leading-tight text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('page-description')
                            <p class="mt-2 max-w-4xl text-sm text-gray-500">@yield('page-description')</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @include('components.flash-messages')

            <!-- Page content -->
            @yield('content')
        </main>
    
        <!-- Footer -->
        @auth
            <footer class="bg-white border-t border-gray-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            © 2025 VetCare. Sistem Manajemen Klinik Hewan.
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500">
                                Versi 1.0.0
                            </span>
                            <div class="flex items-center space-x-1">
                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                <span class="text-sm text-gray-500">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        @endauth
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>