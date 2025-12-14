@extends('layouts.main')

@section('title', 'Diagnoses - VetCare')

@section('page-title', 'Daftar Diagnosis')
@section('page-description', 'Kelola diagnosis untuk rekam medis')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Diagnosis</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola diagnosis untuk rekam medis hewan peliharaan
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('diagnoses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Diagnosis
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('diagnoses.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Diagnosis</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Nama diagnosis atau keterangan..."
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('diagnoses.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Diagnoses List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($diagnoses->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($diagnoses as $diagnosis)
            <li>
                <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                {{ $diagnosis->diagnosis_name }}
                            </h3>
                            @if($diagnosis->description)
                            <p class="mt-2 text-sm text-gray-600">
                                {{ Str::limit($diagnosis->description, 150) }}
                            </p>
                            @endif
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-file-medical text-gray-400 mr-2"></i>
                                <span>Rekam Medis: </span>
                                <a href="{{ route('medical-records.show', $diagnosis->medicalRecord) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                    #{{ $diagnosis->medicalRecord->id }} - {{ $diagnosis->medicalRecord->pet->name }}
                                </a>
                            </div>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <i class="fas fa-stethoscope text-gray-400 mr-2"></i>
                                <span>Dokter: {{ $diagnosis->medicalRecord->doctor->name }}</span>
                            </div>
                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                <span>{{ $diagnosis->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                        <div class="ml-5 flex-shrink-0 flex space-x-2">
                            <a href="{{ route('diagnoses.show', $diagnosis) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-eye mr-1"></i>
                                Lihat
                            </a>
                            <a href="{{ route('diagnoses.edit', $diagnosis) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            <form action="{{ route('diagnoses.destroy', $diagnosis) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus diagnosis ini?');">
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
            {{ $diagnoses->links() }}
        </div>
        @else
        <div class="px-4 py-12 text-center">
            <i class="fas fa-diagnoses text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada diagnosis</h3>
            <p class="text-sm text-gray-600 mb-4">
                @if(request('search'))
                    Tidak ada hasil untuk pencarian "{{ request('search') }}"
                @else
                    Mulai tambahkan diagnosis untuk rekam medis
                @endif
            </p>
            @if(!request('search'))
            <a href="{{ route('diagnoses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>
                Tambah Diagnosis
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
