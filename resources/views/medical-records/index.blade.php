@extends('layouts.main')

@section('title', 'Medical Records - VetCare')

@section('page-title', 'Rekam Medis')
@section('page-description', 'Kelola rekam medis hewan peliharaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rekam Medis</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola rekam medis hewan peliharaan
            </p>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'vet']))
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Rekam Medis
            </a>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('medical-records') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label for="pet" class="block text-sm font-medium text-gray-700">Hewan</label>
                    <select name="pet" id="pet" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Hewan</option>
                        @foreach(\App\Models\Pet::with('customer')->get() as $pet)
                            <option value="{{ $pet->id }}" {{ request('pet') == $pet->id ? 'selected' : '' }}>
                                    {{ $pet->name }} - {{ $pet->customer->name ?? 'Unknown Customer' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="doctor" class="block text-sm font-medium text-gray-700">Dokter</label>
                    <select name="doctor" id="doctor" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Dokter</option>
                        @foreach(\App\Models\Doctor::active()->get() as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
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

    <!-- Medical Records List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($medicalRecords ?? [] as $record)
            <li>
                <a href="{{ route('medical-records.show', $record) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-file-medical text-red-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $record->pet->name ?? 'Unknown Pet' }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $record->doctor->name ?? 'Unknown Doctor' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-diagnosis mr-1.5"></i>
                                                {{ $record->diagnosis ?? 'No Diagnosis' }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-calendar mr-1.5"></i>
                                                {{ $record->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                     {{ $record->pet->customer->name ?? 'Unknown Customer' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $record->treatment ?? 'No Treatment' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-file-medical text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada rekam medis</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan rekam medis baru</p>
                @if(in_array(auth()->user()->role, ['admin', 'vet']))
                <div class="mt-4">
                    <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Rekam Medis Pertama
                    </a>
                </div>
                @endif
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($medicalRecords) && $medicalRecords->hasPages())
    <div class="mt-6">
        {{ $medicalRecords->links() }}
    </div>
    @endif
</div>
@endsection