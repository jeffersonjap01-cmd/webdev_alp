<div x-data="{ mobileOpen: false, profileOpen: false }" class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Left: Brand -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center text-lg font-bold text-gray-900">
                    <i class="fas fa-paw text-2xl text-blue-600 mr-2"></i>
                    <span>VetCare</span>
                </a>
            </div>

            <!-- Center: Navigation (desktop) -->
            <nav class="hidden md:flex md:space-x-2 md:items-center">
                @include('components.navigation-menu')
            </nav>

            <!-- Right: Search / Actions / Profile -->
            <div class="flex items-center space-x-4">
                <div class="hidden sm:block">
                    <div class="relative text-gray-400 focus-within:text-gray-600">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search"></i>
                        </div>
                        <input aria-label="Search" class="block w-64 pl-10 pr-3 py-2 border border-gray-200 rounded-md text-sm placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Cari..." type="search">
                    </div>
                </div>

                <button class="hidden sm:inline-flex p-1 rounded-full text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-bell"></i>
                </button>

                <div class="relative">
                    <button @click="profileOpen = !profileOpen" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}">
                    </button>

                    <div x-show="profileOpen" @click.away="profileOpen = false" x-transition class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                            <div class="font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil Saya</a>
                        <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pengaturan</a>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Keluar</button>
                        </form>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileOpen" class="md:hidden border-t border-gray-100">
        <div class="px-2 pt-2 pb-3 space-y-1">
            @include('components.navigation-menu')
        </div>
    </div>
</div>