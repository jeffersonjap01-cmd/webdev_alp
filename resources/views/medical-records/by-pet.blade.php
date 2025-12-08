@extends('layouts.main')

@section('title', 'Medical Records - ' . ($pet->name ?? 'Pet') . ' - VetCare')

@section('page-title', 'Rekam Medis - ' . ($pet->name ?? 'Unknown Pet'))
@section('page-description', 'Riwayat lengkap rekam medis hewan peliharaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file-medical text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Rekam Medis: {{ $pet->name ?? 'Unknown Pet' }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-tag mr-1.5"></i>
                                {{ $pet->breed ?? 'Unknown Breed' }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-user mr-1.5"></i>
                                {{ $pet->owner->name ?? 'Unknown Owner' }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1.5"></i>
                                Total: {{ $records->count() }} Records
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Pet
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'vet']))
                <a href="{{ route('medical-records.create', ['pet_id' => $pet->id]) }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Rekam Medis
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Medical Records -->
    @if($records->count() > 0)
    <div class="space-y-6">
        @foreach($records as $record)
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-file-medical text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900">
                                    Rekam Medis #{{ $record->id }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $record->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($record->symptoms)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Gejala</h4>
                            <p class="text-sm text-gray-700">{{ $record->symptoms }}</p>
                        </div>
                        @endif

                        @if($record->notes)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Catatan</h4>
                            <p class="text-sm text-gray-700">{{ $record->notes }}</p>
                        </div>
                        @endif

                        @if($record->recommendation)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Rekomendasi</h4>
                            <p class="text-sm text-gray-700">{{ $record->recommendation }}</p>
                        </div>
                        @endif

                        @if($record->doctor)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Dokter</h4>
                            <p class="text-sm text-gray-700">Dr. {{ $record->doctor->name }}</p>
                        </div>
                        @endif

                        @if($record->appointment)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-1">Janji Temu</h4>
                            <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($record->appointment->appointment_time)->format('d M Y, H:i') }}</p>
                        </div>
                        @endif

                        @if($record->diagnoses && $record->diagnoses->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Diagnosis</h4>
                            <div class="space-y-2">
                                @foreach($record->diagnoses as $diagnosis)
                                <div class="bg-gray-50 rounded-md p-3">
                                    <h5 class="font-medium text-sm text-gray-900">{{ $diagnosis->diagnosis_name }}</h5>
                                    @if($diagnosis->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ $diagnosis->description }}</p>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($record->medications && $record->medications->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Obat-obat</h4>
                            <div class="space-y-2">
                                @foreach($record->medications as $medication)
                                <div class="bg-gray-50 rounded-md p-3">
                                    <h5 class="font-medium text-sm text-gray-900">{{ $medication->medicine_name }}</h5>
                                    <div class="text-sm text-gray-600 mt-1 space-y-1">
                                        @if($medication->dosage)
                                        <p><strong>Dosage:</strong> {{ $medication->dosage }}</p>
                                        @endif
                                        @if($medication->frequency)
                                        <p><strong>Frequency:</strong> {{ $medication->frequency }}</p>
                                        @endif
                                        @if($medication->duration)
                                        <p><strong>Duration:</strong> {{ $medication->duration }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="ml-4 flex-shrink-0">
                        <a href="{{ route('medical-records.show', $record) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <i class="fas fa-file-medical text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Rekam Medis</h3>
        <p class="text-gray-500 mb-6">Hewan peliharaan ini belum memiliki rekam medis.</p>
        @if(in_array(auth()->user()->role, ['admin', 'vet']))
        <a href="{{ route('medical-records.create', ['pet_id' => $pet->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-plus mr-2"></i>
            Tambah Rekam Medis Pertama
        </a>
        @endif
    </div>
    @endif
</div>
@endsection