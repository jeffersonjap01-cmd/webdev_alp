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
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-blue-50 text-gray-700">
    <div class="min-h-screen">
        <!-- Navigation -->
        @auth
            @include('components.navigation')
        @endauth

        <!-- Guest Content -->
        @guest
            <!-- Simple guest navigation -->
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <i class="fas fa-paw text-2xl text-blue-600"></i>
                            <span class="font-semibold text-lg text-gray-800">VetCare</span>
                        </a>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">Masuk</a>
                            <a href="{{ route('register') }}" class="ml-3 inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Daftar</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @include('components.flash-messages')

            <!-- Main Content -->
            <main class="flex-1">
                @yield('content')
            </main>
        @endguest

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