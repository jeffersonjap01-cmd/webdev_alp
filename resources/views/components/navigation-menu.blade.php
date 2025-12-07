
<!-- Dashboard - All roles -->
<a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('dashboard*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Dashboard
</a>

<!-- Pets - All authenticated users -->
<a href="{{ route('pets') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('pets*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-paw mr-3 {{ request()->routeIs('pets*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Hewan
</a>

<!-- Appointments - All authenticated users -->
<a href="{{ route('appointments') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('appointments*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-calendar-alt mr-3 {{ request()->routeIs('appointments*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Janji
</a>
