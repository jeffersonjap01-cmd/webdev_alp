<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'VetCare - Professional Pet Care')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-700 bg-white">
    <!-- Header/Navigation -->
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="relative w-12 h-12 flex justify-center items-center">
                <i
                    class="fas fa-dog text-4xl text-primary transform -rotate-12 group-hover:rotate-0 transition-transform duration-300"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-primary leading-none">VetCare</span>
                <span class="text-xs text-blue-400 font-medium tracking-wide">Happy Dog Care</span>
            </div>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8 font-medium text-gray-600">
            <a href="{{ route('home') }}"
                class="text-primary hover:text-primary-dark transition-colors border-b-2 border-primary">Home</a>
            <a href="#about"
                class="hover:text-primary transition-colors border-b-2 border-transparent hover:border-blue-200">About</a>
            <a href="#services"
                class="hover:text-primary transition-colors border-b-2 border-transparent hover:border-blue-200">Boarding
                & Day Care</a>
            <a href="#contact"
                class="hover:text-primary transition-colors border-b-2 border-transparent hover:border-blue-200">Contact</a>
        </div>

        <!-- CTA Buttons -->
        <div class="hidden md:flex items-center gap-4">
            <a href="#services"
                class="bg-blue-300 text-white px-5 py-2 rounded-lg font-medium hover:bg-blue-400 transition shadow-sm hover:shadow-md">
                Our Services <i class="fas fa-chevron-right text-xs ml-1"></i>
            </a>
            @auth
                <a href="{{ route('dashboard') }}"
                    class="bg-primary text-white px-5 py-2 rounded-lg font-medium hover:bg-sky-400 transition shadow-sm hover:shadow-md">
                    Dashboard <i class="fas fa-user text-xs ml-1"></i>
                </a>
            @else
                <a href="{{ route('contact') }}"
                    class="bg-primary text-white px-5 py-2 rounded-lg font-medium hover:bg-sky-400 transition shadow-sm hover:shadow-md">
                    Contact Us <i class="fas fa-chevron-right text-xs ml-1"></i>
                </a>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden text-gray-600 text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-blue-50 py-12 mt-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-between items-center">
                <div class="w-full md:w-auto mb-6 md:mb-0">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-dog text-2xl text-primary"></i>
                        <span class="text-xl font-bold text-gray-800">VetCare</span>
                    </div>
                    <p class="text-gray-500 text-sm max-w-xs">
                        Professional, reliable & trustworthy doggy daycare and boarding service.
                    </p>
                </div>

                <div class="flex gap-4">
                    <a href="#"
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary shadow-sm hover:scale-110 transition-transform">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary shadow-sm hover:scale-110 transition-transform">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#"
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary shadow-sm hover:scale-110 transition-transform">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
            <div class="border-t border-blue-100 mt-8 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} VetCare. All rights reserved.
            </div>
        </div>
    </footer>
</body>

</html>