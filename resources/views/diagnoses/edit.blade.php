@extends('layouts.main')

@section('title', 'Edit Diagnosis - VetCare')

@section('page-title', 'Edit Diagnosis')
@section('page-description', 'Edit informasi diagnosis')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Edit Informasi Diagnosis
            </h3>
            
            <form action="{{ route('diagnoses.update', $diagnosis) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Medical Record Selection -->
                <div class="mb-4">
                    <label for="medical_record_id" class="block text-sm font-medium text-gray-700">
                        Rekam Medis <span class="text-red-500">*</span>
                    </label>
                    <select name="medical_record_id" id="medical_record_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('medical_record_id') border-red-500 @enderror">
                        <option value="">Pilih Rekam Medis</option>
                        @foreach($medicalRecords as $record)
                            <option value="{{ $record->id }}" {{ old('medical_record_id', $diagnosis->medical_record_id) == $record->id ? 'selected' : '' }}>
                                Rekam Medis #{{ $record->id }} - {{ $record->pet->name }} ({{ $record->visit_date }})
                            </option>
                        @endforeach
                    </select>
                    @error('medical_record_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Diagnosis Name -->
                <div class="mb-4">
                    <label for="diagnosis_name" class="block text-sm font-medium text-gray-700">
                        Nama Diagnosis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="diagnosis_name" id="diagnosis_name" required value="{{ old('diagnosis_name', $diagnosis->diagnosis_name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('diagnosis_name') border-red-500 @enderror"
                           placeholder="Contoh: Infeksi Telinga">
                    @error('diagnosis_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Keterangan
                    </label>
                    <textarea name="description" id="description" rows="6"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-500 @enderror"
                              placeholder="Keterangan detail tentang diagnosis...">{{ old('description', $diagnosis->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Berikan keterangan detail tentang diagnosis, termasuk gejala, penyebab, dan rekomendasi perawatan
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('diagnoses.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Diagnosis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
