@extends('layouts.main')

@section('title', 'Customers - VetCare')

@section('page-title', 'Customer')
@section('page-description', 'Kelola data customer')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customer</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola data customer hewan peliharaan
            </p>
        </div>
        {{-- Admin may not create customers via UI anymore --}}
    </div>

    <!-- Search and Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('customers') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div class="sm:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Cari nama, email, atau telepon"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Owners List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($customers ?? [] as $customer)
            <li>
                <a href="{{ route('customers.show', $customer) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $customer->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                            <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $customer->name }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Aktif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-envelope mr-1.5"></i>
                                                {{ $customer->email }}
                                            </p>
                                            @if($customer->phone)
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-phone mr-1.5"></i>
                                                {{ $customer->phone }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    <i class="fas fa-paw mr-1"></i>
                                    {{ $customer->pets()->count() }} Hewan
                                </p>
                                <p class="text-sm text-gray-500">
                                    Bergabung: {{ $customer->created_at ? $customer->created_at->format('M Y') : 'Unknown' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada customer</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan customer baru</p>
                {{-- Admin cannot add customers here; use public registration instead --}}
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($customers) && $customers->hasPages())
    <div class="mt-6">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection