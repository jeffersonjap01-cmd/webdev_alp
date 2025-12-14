@extends('layouts.main')

@section('title', 'Medical Record Details - VetCare')

@section('page-title', 'Detail Rekam Medis')
@section('page-description', 'Informasi lengkap rekam medis')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-file-medical text-2xl text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Rekam Medis - {{ $record->pet->name ?? 'Unknown Pet' }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-user-md mr-1.5"></i>
                                Dr. {{ $record->doctor->name ?? 'Unknown Doctor' }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1.5"></i>
                                {{ $record->created_at->format('d M Y, H:i') }}
                            </div>
                            @if($record->appointment)
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar-check mr-1.5"></i>
                                Janji Temu: {{ \Carbon\Carbon::parse($record->appointment->appointment_time)->format('d M Y, H:i') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('medical-records') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Medical Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Medis</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Diagnosis</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->diagnosis ?? 'Tidak ada diagnosis' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dokter</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->doctor->name ?? 'Unknown Doctor' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Perawatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Biaya</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->cost ? 'Rp ' . number_format($record->cost, 0, ',', '.') : 'Tidak disebutkan' }}</dd>
                        </div>
                        @if($record->treatment)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Perawatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->treatment }}</dd>
                        </div>
                        @endif
                        @if($record->symptoms)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Gejala</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->symptoms }}</dd>
                        </div>
                        @endif
                        @if($record->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Prescriptions -->
            @if($record->prescriptions && $record->prescriptions->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Resep Obat</h3>
                        <a href="{{ route('prescriptions') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($record->prescriptions as $prescription)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $prescription->medication->name ?? 'Unknown Medication' }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $prescription->dosage }}</p>
                                    <p class="text-sm text-gray-500">{{ $prescription->frequency }} - {{ $prescription->duration }}</p>
                                    @if($prescription->instructions)
                                    <p class="text-sm text-gray-500 mt-1">{{ $prescription->instructions }}</p>
                                    @endif
                                </div>
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
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Attachments -->
            @if($record->attachments && $record->attachments->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Lampiran</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($record->attachments as $attachment)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-file text-gray-400 text-2xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $attachment->name }}</p>
                                    <p class="text-sm text-gray-500">{{ number_format($attachment->size / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <a href="{{ $attachment->url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-500">
                                    <i class="fas fa-download mr-1"></i>
                                    Download
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pet Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Hewan</h3>
                    <dl class="space-y-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-paw text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">{{ $record->pet->name ?? 'Unknown' }}</dt>
                                <dd class="text-sm text-gray-500">{{ $record->pet->type ?? 'Unknown Type' }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Breed</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->pet->breed ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Umur</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->pet->age ?? 'Unknown' }} tahun</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Berat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->pet->weight ?? 'Unknown' }} kg</dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('pets.show', $record->pet) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-paw mr-2"></i>
                            Lihat Profil Hewan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Customer</h3>
                    <dl class="space-y-4">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($record->pet->customer->name ?? 'Unknown') }}&color=7F9CF5&background=EBF4FF" alt="{{ $record->pet->customer->name ?? 'Unknown' }}">
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">{{ $record->pet->customer->name ?? 'Unknown' }}</dt>
                                <dd class="text-sm text-gray-500">{{ $record->pet->customer->email ?? 'Unknown' }}</dd>
                            </div>
                        </div>
                        @if($record->pet->customer && $record->pet->customer->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->pet->customer->phone }}</dd>
                        </div>
                        @endif
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('customers.show', $record->pet->customer) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i>
                            Lihat Profil Customer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Appointment Information -->
            @if($record->appointment)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Janji Temu</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->appointment->appointment_time)->format('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Layanan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $record->appointment->service_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    @switch($record->appointment->status)
                                        @case('scheduled') bg-yellow-100 text-yellow-800 @break
                                        @case('confirmed') bg-green-100 text-green-800 @break
                                        @case('completed') bg-blue-100 text-blue-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($record->appointment->status)
                                        @case('scheduled') Terjadwal @break
                                        @case('confirmed') Dikonfirmasi @break
                                        @case('completed') Selesai @break
                                        @case('cancelled') Dibatalkan @break
                                        @default {{ ucfirst($record->appointment->status) }}
                                    @endswitch
                                </span>
                            </dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('appointments.show', $record->appointment) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-calendar mr-2"></i>
                            Lihat Janji Temu
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection