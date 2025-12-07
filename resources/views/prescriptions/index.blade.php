@extends('layouts.main')

@section('title', 'Prescriptions - VetCare')

@section('page-title', 'Resep Obat')
@section('page-description', 'Kelola resep obat hewan peliharaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Resep Obat</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola resep obat hewan peliharaan
            </p>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'vet']))
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('prescriptions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Resep
            </a>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('prescriptions') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>Diisi</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                
                <div>
                    <label for="pet" class="block text-sm font-medium text-gray-700">Hewan</label>
                    <select name="pet" id="pet" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Hewan</option>
                        @foreach(\App\Models\Pet::with('owner')->get() as $pet)
                            <option value="{{ $pet->id }}" {{ request('pet') == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }} - {{ $pet->owner->name ?? 'Unknown Owner' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </form>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($prescriptions ?? [] as $prescription)
            <li>
                <a href="{{ route('prescriptions.show', $prescription) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-pills text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $prescription->medication->name ?? 'Unknown Medication' }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @switch($prescription->status)
                                                    @case('pending') bg-yellow-100 text-yellow-800 @break
                                                    @case('filled') bg-green-100 text-green-800 @break
                                                    @case('cancelled') bg-red-100 text-red-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch
                                            ">
                                                @switch($prescription->status)
                                                    @case('pending') Pending @break
                                                    @case('filled') Diisi @break
                                                    @case('cancelled') Dibatalkan @break
                                                    @default {{ ucfirst($prescription->status) }}
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-paw mr-1.5"></i>
                                                {{ $prescription->pet->name ?? 'Unknown Pet' }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-user mr-1.5"></i>
                                                {{ $prescription->pet->owner->name ?? 'Unknown Owner' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    {{ $prescription->dosage }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $prescription->frequency }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-pills text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada resep obat</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan resep obat baru</p>
                @if(in_array(auth()->user()->role, ['admin', 'vet']))
                <div class="mt-4">
                    <a href="{{ route('prescriptions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Resep Pertama
                    </a>
                </div>
                @endif
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($prescriptions) && $prescriptions->hasPages())
    <div class="mt-6">
        {{ $prescriptions->links() }}
    </div>
    @endif
</div>
@endsection