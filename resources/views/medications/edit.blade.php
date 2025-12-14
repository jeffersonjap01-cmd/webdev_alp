@extends('layouts.main')

@section('title', 'Edit Obat - VetCare')

@section('page-title', 'Edit Obat')
@section('page-description', 'Edit informasi obat')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Edit Informasi Obat
            </h3>
            
            <form action="{{ route('medications.update', $medication) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Prescription Selection -->
                <div class="mb-4">
                    <label for="prescription_id" class="block text-sm font-medium text-gray-700">
                        Resep <span class="text-red-500">*</span>
                    </label>
                    <select name="prescription_id" id="prescription_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('prescription_id') border-red-500 @enderror">
                        <option value="">Pilih Resep</option>
                        @foreach($prescriptions as $prescription)
                            <option value="{{ $prescription->id }}" {{ old('prescription_id', $medication->prescription_id) == $prescription->id ? 'selected' : '' }}>
                                Resep #{{ $prescription->id }} - {{ $prescription->medicalRecord->pet->name }} ({{ $prescription->date }})
                            </option>
                        @endforeach
                    </select>
                    @error('prescription_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Medication Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nama Obat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $medication->name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                           placeholder="Contoh: Amoxicillin">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Dosage -->
                <div class="mb-4">
                    <label for="dosage" class="block text-sm font-medium text-gray-700">
                        Dosis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="dosage" id="dosage" required value="{{ old('dosage', $medication->dosage) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('dosage') border-red-500 @enderror"
                           placeholder="Contoh: 250mg">
                    @error('dosage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Frequency -->
                <div class="mb-4">
                    <label for="frequency" class="block text-sm font-medium text-gray-700">
                        Frekuensi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="frequency" id="frequency" required value="{{ old('frequency', $medication->frequency) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('frequency') border-red-500 @enderror"
                           placeholder="Contoh: 2 kali sehari">
                    @error('frequency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Duration -->
                <div class="mb-4">
                    <label for="duration" class="block text-sm font-medium text-gray-700">
                        Durasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="duration" id="duration" required value="{{ old('duration', $medication->duration) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('duration') border-red-500 @enderror"
                           placeholder="Contoh: 7 hari">
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Instructions -->
                <div class="mb-6">
                    <label for="instructions" class="block text-sm font-medium text-gray-700">
                        Instruksi Penggunaan
                    </label>
                    <textarea name="instructions" id="instructions" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('instructions') border-red-500 @enderror"
                              placeholder="Instruksi tambahan untuk penggunaan obat...">{{ old('instructions', $medication->instructions) }}</textarea>
                    @error('instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Berikan instruksi detail untuk penggunaan obat
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('medications.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Obat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
