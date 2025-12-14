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
        <!-- Navigation component contains the sidebar and main content area -->
        @include('components.navigation')

        <!-- Footer (always rendered) -->
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
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>

</html>