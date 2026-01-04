@extends('layouts.main')

@section('title', 'Create Prescription - VetCare')

@section('page-title', 'Tambah Resep Obat')
@section('page-description', 'Tambahkan resep obat baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Tambah Resep Obat Baru
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    @if($medicalRecord)
                        Buat resep untuk rekam medis yang baru saja dibuat
                    @else
                        Isi form di bawah untuk menambahkan resep obat baru
                    @endif
                </p>
            </div>
            <div class="mt-4 flex space-x-2 md:mt-0 md:ml-4">
                @if($medicalRecord)
                    <a href="{{ route('appointments.show', $medicalRecord->appointment_id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Appointment
                    </a>
                @else
                    <a href="{{ route('prescriptions') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('prescriptions.store') }}" method="POST" class="space-y-6">
                @csrf
                
                @if($medicalRecord)
                    <input type="hidden" name="medical_record_id" value="{{ $medicalRecord->id }}">
                    <input type="hidden" name="pet_id" value="{{ $medicalRecord->pet_id }}">
                    <input type="hidden" name="doctor_id" value="{{ $medicalRecord->doctor_id }}">
                    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                    
                    <!-- Patient Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-blue-900 mb-2">Informasi Pasien</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Hewan:</span>
                                <span class="font-medium ml-2">{{ $medicalRecord->pet->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Dokter:</span>
                                <span class="font-medium ml-2">{{ $medicalRecord->doctor->name }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medications from Medical Record -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Obat yang Diresepkan</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="grid grid-cols-4 gap-2 mb-2 text-xs font-semibold text-gray-600">
                                <div class="pl-2">Nama Obat</div>
                                <div class="pl-2">Dosis</div>
                                <div class="pl-2">Frekuensi</div>
                                <div class="pl-2">Durasi</div>
                            </div>
                            @foreach($medications as $medication)
                                <div class="grid grid-cols-4 gap-2 mb-2 py-2 bg-white rounded border border-gray-200">
                                    <div class="pl-2 text-sm">{{ $medication->medicine_name }}</div>
                                    <div class="pl-2 text-sm">{{ $medication->dosage }}</div>
                                    <div class="pl-2 text-sm">{{ $medication->frequency }}</div>
                                    <div class="pl-2 text-sm">{{ $medication->duration }}</div>
                                </div>
                            @endforeach
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle"></i> Obat-obatan di atas akan otomatis ditambahkan ke resep ini.
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Basic Information for standalone prescription -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                        
                        <div>
                            <label for="pet_id" class="block text-sm font-medium text-gray-700">Hewan Peliharaan *</label>
                            <select name="pet_id" id="pet_id" required class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">Pilih Hewan Peliharaan</option>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}">{{ $pet->name }} - {{ optional($pet->user)->name ?? 'No Owner' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="doctor_id" class="block text-sm font-medium text-gray-700">Dokter *</label>
                            <select name="doctor_id" id="doctor_id" required class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">Pilih Dokter</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->name }} - {{ $doctor->specialization }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal *</label>
                            <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" required class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>
                    </div>
                @endif
                <p class="mt-1 text-sm text-gray-500">Opsional. Pilih rekam medis terkait jika ada.</p>
                
                <!-- Prescription Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Detail Resep</h3>
                    
                    <!-- Diagnosis -->
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis *</label>
                        <textarea name="diagnosis" id="diagnosis" rows="2" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-md hover:border-blue-500 transition-all @error('diagnosis') border-red-300 @enderror" 
                                  placeholder="Masukkan diagnosis">{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions -->
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700">Instruksi Penggunaan *</label>
                        <textarea name="instructions" id="instructions" rows="3" required
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-md hover:border-blue-500 transition-all @error('instructions') border-red-300 @enderror" 
                                  placeholder="Instruksi untuk pemilik hewan">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    @if($medicalRecord)
                        <a href="{{ route('appointments.show', $medicalRecord->appointment_id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </a>
                    @else
                        <a href="{{ route('prescriptions') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </a>
                    @endif
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Resep
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection