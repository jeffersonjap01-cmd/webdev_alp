<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100">
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
        
        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <div class="flex items-center">
                        <i class="fas fa-paw text-2xl text-blue-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">VetCare</span>
                    </div>
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    @include('components.navigation-menu')
                </nav>
            </div>
        </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden md:flex md:flex-shrink-0">
        <div class="flex flex-col w-64">
            <div class="flex flex-col flex-grow pt-5 bg-white border-r border-gray-200 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <div class="flex items-center">
                        <i class="fas fa-paw text-2xl text-blue-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">VetCare</span>
                    </div>
                </div>
                <div class="mt-5 flex-grow flex flex-col">
                    <nav class="flex-1 px-2 pb-4 space-y-1">
                        @include('components.navigation-menu')
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <!-- Top navigation -->
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
            <button @click="sidebarOpen = true" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex-1 px-4 flex justify-between">
                <div class="flex-1 flex">
                    <div class="w-full flex md:ml-0">
                        <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                            <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                <i class="fas fa-search"></i>
                            </div>
                            <input class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm" placeholder="Cari..." type="search">
                        </div>
                    </div>
                </div>
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Notifications -->
                    <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-bell"></i>
                        <span class="sr-only">View notifications</span>
                    </button>

                    <!-- Profile dropdown -->
                    <div x-data="{ profileOpen: false }" class="ml-3 relative">
                        <div>
                            <button @click="profileOpen = !profileOpen" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}">
                            </button>
                        </div>
                        <div x-show="profileOpen" @click.away="profileOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                <div class="font-medium">{{ auth()->user()->name }}</div>
                                <div class="text-gray-500">{{ auth()->user()->email }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    @switch(auth()->user()->role)
                                        @case('admin')
                                            Administrator
                                            @break
                                        @case('vet')
                                            Dokter Hewan
                                            @break
                                        @case('customer')
                                            Pemilik Hewan
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                            <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Pengaturan
                            </a>
                            <form method="POST" action="/logout" class="block">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <!-- Page header -->
                    <div class="pb-5 border-b border-gray-200">
                        <h1 class="text-3xl font-bold leading-tight text-gray-900">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        @hasSection('page-description')
                            <p class="mt-2 max-w-4xl text-sm text-gray-500">
                                @yield('page-description')
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @include('components.flash-messages')

            <!-- Main Content -->
            @yield('content')
        </main>
    </div>
</div>