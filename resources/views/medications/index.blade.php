@extends('layouts.main')

@section('title', 'Medications - VetCare')

@section('page-title', 'Daftar Obat')
@section('page-description', 'Kelola obat-obatan untuk resep')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Obat</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola obat-obatan untuk resep hewan peliharaan
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('medications.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Obat
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('medications') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Obat</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Nama obat, dosis, atau instruksi..."
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('medications') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Medications List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($medications->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($medications as $medication)
            <li>
                <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                {{ $medication->name }}
                            </h3>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm text-gray-600">
                                <div>
                                    <i class="fas fa-prescription-bottle text-gray-400"></i>
                                    <span class="ml-1">Dosis: {{ $medication->dosage }}</span>
                                </div>
                                <div>
                                    <i class="fas fa-clock text-gray-400"></i>
                                    <span class="ml-1">Frekuensi: {{ $medication->frequency }}</span>
                                </div>
                                <div>
                                    <i class="fas fa-calendar text-gray-400"></i>
                                    <span class="ml-1">Durasi: {{ $medication->duration }}</span>
                                </div>
                            </div>
                            @if($medication->instructions)
                            <p class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-info-circle text-gray-400"></i>
                                {{ Str::limit($medication->instructions, 100) }}
                            </p>
                            @endif
                            <div class="mt-2 text-sm text-gray-500">
                                <i class="fas fa-stethoscope text-gray-400"></i>
                                Resep: <a href="{{ route('prescriptions.show', $medication->prescription) }}" class="text-blue-600 hover:text-blue-800">
                                    #{{ $medication->prescription->id }}
                                </a>
                            </div>
                        </div>
                        <div class="ml-5 flex-shrink-0 flex space-x-2">
                            <a href="{{ route('medications.show', $medication) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-eye mr-1"></i>
                                Lihat
                            </a>
                            <a href="{{ route('medications.edit', $medication) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            <form action="{{ route('medications.destroy', $medication) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        
        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $medications->links() }}
        </div>
        @else
        <div class="px-4 py-12 text-center">
            <i class="fas fa-pills text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada obat</h3>
            <p class="text-sm text-gray-600 mb-4">
                @if(request('search'))
                    Tidak ada hasil untuk pencarian "{{ request('search') }}"
                @else
                    Mulai tambahkan obat untuk resep
                @endif
            </p>
            @if(!request('search'))
            <a href="{{ route('medications.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Tambah Obat
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
