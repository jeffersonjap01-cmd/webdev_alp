@extends('layouts.main')

@section('title', 'Edit Prescription - VetCare')

@section('page-title', 'Edit Resep Obat')
@section('page-description', 'Edit informasi resep obat')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Resep Obat - {{ $prescription->medication->name ?? 'Unknown Medication' }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Edit informasi resep obat
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('prescriptions.show', $prescription) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('prescriptions.update', $prescription) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                    
                    <!-- Pet Selection -->
                    <div>
                        <label for="pet_id" class="block text-sm font-medium text-gray-700">Hewan Peliharaan *</label>
                        <select name="pet_id" id="pet_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('pet_id') border-red-300 @enderror">
                            <option value="">Pilih Hewan Peliharaan</option>
                            @foreach($pets ?? \App\Models\Pet::with('owner')->get() as $pet)
                                <option value="{{ $pet->id }}" {{ old('pet_id', $prescription->pet_id) == $pet->id ? 'selected' : '' }}>
                                    {{ $pet->name }} - {{ $pet->owner->name ?? 'No Owner' }} ({{ $pet->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('pet_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Medication Selection -->
                    <div>
                        <label for="medication_id" class="block text-sm font-medium text-gray-700">Obat *</label>
                        <select name="medication_id" id="medication_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('medication_id') border-red-300 @enderror">
                            <option value="">Pilih Obat</option>
                            @foreach($medications ?? \App\Models\Medication::get() as $medication)
                                <option value="{{ $medication->id }}" {{ old('medication_id', $prescription->medication_id) == $medication->id ? 'selected' : '' }}>
                                    {{ $medication->name }} - {{ $medication->category ?? 'Unknown Category' }}
                                </option>
                            @endforeach
                        </select>
                        @error('medication_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Medical Record Selection -->
                    <div>
                        <label for="medical_record_id" class="block text-sm font-medium text-gray-700">Rekam Medis (Opsional)</label>
                        <select name="medical_record_id" id="medical_record_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('medical_record_id') border-red-300 @enderror">
                            <option value="">Pilih Rekam Medis</option>
                            @if(isset($medicalRecords))
                                @foreach($medicalRecords as $record)
                                    <option value="{{ $record->id }}" {{ old('medical_record_id', $prescription->medical_record_id) == $record->id ? 'selected' : '' }}>
                                        {{ $record->pet->name }} - {{ $record->diagnosis }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('medical_record_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Prescription Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Detail Resep</h3>
                    
                    <!-- Dosage -->
                    <div>
                        <label for="dosage" class="block text-sm font-medium text-gray-700">Dosis *</label>
                        <input type="text" name="dosage" id="dosage" required 
                               value="{{ old('dosage', $prescription->dosage) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('dosage') border-red-300 @enderror" 
                               placeholder="Contoh: 1 tablet, 5ml, 2 kapsul">
                        @error('dosage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Frequency -->
                    <div>
                        <label for="frequency" class="block text-sm font-medium text-gray-700">Frekuensi *</label>
                        <select name="frequency" id="frequency" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('frequency') border-red-300 @enderror">
                            <option value="">Pilih Frekuensi</option>
                            <option value="1x sehari" {{ old('frequency', $prescription->frequency) == '1x sehari' ? 'selected' : '' }}>1x sehari</option>
                            <option value="2x sehari" {{ old('frequency', $prescription->frequency) == '2x sehari' ? 'selected' : '' }}>2x sehari</option>
                            <option value="3x sehari" {{ old('frequency', $prescription->frequency) == '3x sehari' ? 'selected' : '' }}>3x sehari</option>
                            <option value="4x sehari" {{ old('frequency', $prescription->frequency) == '4x sehari' ? 'selected' : '' }}>4x sehari</option>
                            <option value="Setiap 4 jam" {{ old('frequency', $prescription->frequency) == 'Setiap 4 jam' ? 'selected' : '' }}>Setiap 4 jam</option>
                            <option value="Setiap 6 jam" {{ old('frequency', $prescription->frequency) == 'Setiap 6 jam' ? 'selected' : '' }}>Setiap 6 jam</option>
                            <option value="Setiap 8 jam" {{ old('frequency', $prescription->frequency) == 'Setiap 8 jam' ? 'selected' : '' }}>Setiap 8 jam</option>
                            <option value="Setiap 12 jam" {{ old('frequency', $prescription->frequency) == 'Setiap 12 jam' ? 'selected' : '' }}>Setiap 12 jam</option>
                            <option value="Sesuai kebutuhan" {{ old('frequency', $prescription->frequency) == 'Sesuai kebutuhan' ? 'selected' : '' }}>Sesuai kebutuhan</option>
                        </select>
                        @error('frequency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Durasi *</label>
                        <select name="duration" id="duration" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('duration') border-red-300 @enderror">
                            <option value="">Pilih Durasi</option>
                            <option value="1 hari" {{ old('duration', $prescription->duration) == '1 hari' ? 'selected' : '' }}>1 hari</option>
                            <option value="2 hari" {{ old('duration', $prescription->duration) == '2 hari' ? 'selected' : '' }}>2 hari</option>
                            <option value="3 hari" {{ old('duration', $prescription->duration) == '3 hari' ? 'selected' : '' }}>3 hari</option>
                            <option value="5 hari" {{ old('duration', $prescription->duration) == '5 hari' ? 'selected' : '' }}>5 hari</option>
                            <option value="7 hari" {{ old('duration', $prescription->duration) == '7 hari' ? 'selected' : '' }}>7 hari (1 minggu)</option>
                            <option value="10 hari" {{ old('duration', $prescription->duration) == '10 hari' ? 'selected' : '' }}>10 hari</option>
                            <option value="14 hari" {{ old('duration', $prescription->duration) == '14 hari' ? 'selected' : '' }}>14 hari (2 minggu)</option>
                            <option value="21 hari" {{ old('duration', $prescription->duration) == '21 hari' ? 'selected' : '' }}>21 hari (3 minggu)</option>
                            <option value="30 hari" {{ old('duration', $prescription->duration) == '30 hari' ? 'selected' : '' }}>30 hari (1 bulan)</option>
                            <option value="until finished" {{ old('duration', $prescription->duration) == 'until finished' ? 'selected' : '' }}>Hasta habis</option>
                        </select>
                        @error('duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions -->
                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700">Instruksi Penggunaan</label>
                        <textarea name="instructions" id="instructions" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('instructions') border-red-300 @enderror" 
                                  placeholder="Contoh: Berikan setelah makan, Campurkan dengan makanan, dll">{{ old('instructions', $prescription->instructions) }}</textarea>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-300 @enderror" 
                                  placeholder="Catatan tambahan">{{ old('notes', $prescription->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('status') border-red-300 @enderror">
                            <option value="pending" {{ old('status', $prescription->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="filled" {{ old('status', $prescription->status) == 'filled' ? 'selected' : '' }}>Diisi</option>
                            <option value="cancelled" {{ old('status', $prescription->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('prescriptions.show', $prescription) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Resep
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection