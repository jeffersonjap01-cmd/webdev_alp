@extends('layouts.main')

@section('title', 'Detail Obat - VetCare')

@section('page-title', 'Detail Obat')
@section('page-description', 'Informasi lengkap obat')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header with Actions -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $medication->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Detail informasi obat</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('medications.edit', $medication) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-edit mr-2"></i>
                Edit
            </a>
            <form action="{{ route('medications.destroy', $medication) }}" method="POST" class="inline" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus
                </button>
            </form>
            <a href="{{ route('medications') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Medication Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Informasi Obat
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-pills text-gray-400"></i> Nama Obat
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $medication->name }}
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-prescription-bottle text-gray-400"></i> Dosis
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $medication->dosage }}
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-clock text-gray-400"></i> Frekuensi
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $medication->frequency }}
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-calendar text-gray-400"></i> Durasi
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $medication->duration }}
                    </dd>
                </div>
                
                @if($medication->instructions)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-info-circle text-gray-400"></i> Instruksi
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $medication->instructions }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Prescription Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Informasi Resep
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-file-prescription text-gray-400"></i> Nomor Resep
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('prescriptions.show', $medication->prescription) }}" class="text-blue-600 hover:text-blue-800">
                            #{{ $medication->prescription->id }}
                        </a>
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-calendar-alt text-gray-400"></i> Tanggal Resep
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ \Carbon\Carbon::parse($medication->prescription->date)->format('d M Y') }}
                    </dd>
                </div>
                
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-paw text-gray-400"></i> Hewan
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('pets.show', $medication->prescription->medicalRecord->pet) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $medication->prescription->medicalRecord->pet->name }}
                        </a>
                    </dd>
                </div>
                
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <i class="fas fa-stethoscope text-gray-400"></i> Dokter
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $medication->prescription->medicalRecord->doctor->name }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Timeline
            </h3>
        </div>
        <div class="px-4 py-5 sm:px-6">
            <div class="flow-root">
                <ul class="-mb-8">
                    <li>
                        <div class="relative pb-8">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Obat ditambahkan
                                        </p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        {{ $medication->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    @if($medication->updated_at != $medication->created_at)
                    <li>
                        <div class="relative">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                        <i class="fas fa-edit text-white text-xs"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Obat diupdate
                                        </p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        {{ $medication->updated_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
