<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-blue-50">
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75"
            @click="sidebarOpen = false"></div>

        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative flex-1 flex flex-col max-w-xs w-full bg-white rounded-r-2xl shadow-xl">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="sidebarOpen = false"
                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-dog text-3xl text-primary transform -rotate-12"></i>
                        <div>
                            <span class="text-xl font-bold text-primary block leading-none">VetCare</span>
                            <span class="text-xs text-blue-300 font-bold tracking-wider">DASHBOARD</span>
                        </div>
                    </div>
                </div>
                <nav class="mt-8 px-4 space-y-2">
                    @include('components.navigation-menu')
                </nav>
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-72 m-4 rounded-3xl bg-white shadow-lg border border-blue-50 overflow-hidden">
            <div class="flex flex-col flex-grow pt-8 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-8 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center">
                            <i class="fas fa-dog text-2xl text-primary"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-gray-800 block leading-none">VetCare</span>
                            <span class="text-xs text-blue-400 font-bold tracking-wider">ADMIN PANEL</span>
                        </div>
                    </div>
                </div>
                <div class="flex-grow flex flex-col">
                    <nav class="flex-1 px-4 space-y-2">
                        @include('components.navigation-menu')
                    </nav>
                </div>

                <!-- Sidebar Footer -->
                <div class="p-4 border-t border-gray-100">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                            class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-500 rounded-xl hover:bg-red-50 transition-colors">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <!-- Top navigation -->
        <div class="relative z-10 flex-shrink-0 flex h-20 bg-transparent">
            <button @click="sidebarOpen = true"
                class="px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex-1 px-8 flex justify-between items-center">
                <div class="flex-1 flex">
                    <h2 class="text-2xl font-bold text-gray-800 hidden md:block">
                        @yield('page-title')
                    </h2>
                </div>
                <div class="ml-4 flex items-center md:ml-6 gap-4">
                    <!-- Notifications -->
                    <button
                        class="bg-white p-2 rounded-full text-gray-400 hover:text-primary shadow-sm hover:shadow-md transition focus:outline-none">
                        <i class="fas fa-bell"></i>
                    </button>

                    <!-- Profile dropdown -->
                    <div x-data="{ profileOpen: false }" class="relative">
                        @auth
                            <div>
                                <button @click="profileOpen = !profileOpen"
                                    class="flex items-center gap-3 focus:outline-none">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-sm font-bold text-gray-700">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                                    </div>
                                    <img class="h-10 w-10 rounded-full border-2 border-white shadow-md"
                                        src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=7F9CF5&background=EBF4FF"
                                        alt="{{ auth()->user()->name }}">
                                </button>
                            </div>
                            <div x-show="profileOpen" @click.away="profileOpen = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="origin-top-right absolute right-0 mt-2 w-48 rounded-2xl shadow-xl py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="px-4 py-3 border-b border-gray-100 bg-blue-50">
                                    <p class="text-sm font-bold text-gray-800">My Account</p>
                                </div>
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-primary transition-colors">
                                    <i class="fas fa-user mr-2 text-xs"></i> Profile
                                </a>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-primary transition-colors">
                                    <i class="fas fa-cog mr-2 text-xs"></i> Settings
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2 text-xs"></i> Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center gap-3">
                                <a href="{{ route('login') }}" class="text-sm text-primary font-semibold">Login</a>
                                <a href="{{ route('register') }}" class="ml-3 text-sm bg-primary text-white px-3 py-2 rounded-lg shadow-sm">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none p-4 md:p-8 pt-0">
            <!-- Flash Messages -->
            @include('components.flash-messages')

            <!-- Main Content -->
            @yield('content')
        </main>
    </div>
</div>