
<!-- Dashboard - All roles -->
<a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-tachometer-alt mr-3 {{ request()->routeIs('dashboard*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Dashboard
</a>

<!-- Pets - All authenticated users -->
<a href="{{ route('pets.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('pets*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-paw mr-3 {{ request()->routeIs('pets*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Hewan
</a>

<!-- Appointments - All authenticated users -->
<a href="{{ route('appointments') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('appointments*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-calendar-alt mr-3 {{ request()->routeIs('appointments*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Janji
</a>

<!-- Medical Records - Doctor & Admin -->
<a href="{{ route('medical-records') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('medical-records*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-file-medical mr-3 {{ request()->routeIs('medical-records*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Rekam Medis
</a>

@if(auth()->check() && (auth()->user()->role === 'doctor' || auth()->user()->role === 'admin'))


<!-- Prescriptions - Doctor & Admin -->
<a href="{{ route('prescriptions') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('prescriptions*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-file-prescription mr-3 {{ request()->routeIs('prescriptions*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Resep
</a>

<!-- Medications - Doctor & Admin -->
<a href="{{ route('medications') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('medications*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-pills mr-3 {{ request()->routeIs('medications*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Obat
</a>

<!-- Diagnoses - Doctor & Admin -->
<a href="{{ route('diagnoses.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('diagnoses*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-500 hover:text-gray-900' }}">
    <i class="fas fa-diagnoses mr-3 {{ request()->routeIs('diagnoses*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
    Diagnosis
</a>
@endif
