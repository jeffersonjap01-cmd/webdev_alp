@extends('layouts.main')

@section('title', 'Edit Doctor - VetCare')

@section('page-title', 'Edit Dokter')
@section('page-description', 'Edit informasi dokter')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Dokter - {{ $doctor->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Edit informasi dokter
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('doctors.show', $doctor) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('doctors.update', $doctor) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Pribadi</h3>
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" required 
                               value="{{ old('name', $doctor->name) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror" 
                               placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="email" id="email" required 
                               value="{{ old('email', $doctor->email) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror" 
                               placeholder="dokter@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" 
                               value="{{ old('phone', $doctor->phone) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone') border-red-300 @enderror" 
                               placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Spesialisasi *</label>
                        <select name="specialization" id="specialization" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('specialization') border-red-300 @enderror">
                            <option value="">Pilih Spesialisasi</option>
                            <option value="General Practice" {{ old('specialization', $doctor->specialization) == 'General Practice' ? 'selected' : '' }}>General Practice</option>
                            <option value="Surgery" {{ old('specialization', $doctor->specialization) == 'Surgery' ? 'selected' : '' }}>Surgery</option>
                            <option value="Dermatology" {{ old('specialization', $doctor->specialization) == 'Dermatology' ? 'selected' : '' }}>Dermatology</option>
                            <option value="Cardiology" {{ old('specialization', $doctor->specialization) == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                            <option value="Orthopedics" {{ old('specialization', $doctor->specialization) == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                            <option value="Oncology" {{ old('specialization', $doctor->specialization) == 'Oncology' ? 'selected' : '' }}>Oncology</option>
                            <option value="Neurology" {{ old('specialization', $doctor->specialization) == 'Neurology' ? 'selected' : '' }}>Neurology</option>
                            <option value="Ophthalmology" {{ old('specialization', $doctor->specialization) == 'Ophthalmology' ? 'selected' : '' }}>Ophthalmology</option>
                        </select>
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- License Number -->
                    <div>
                        <label for="license_number" class="block text-sm font-medium text-gray-700">Nomor Lisensi</label>
                        <input type="text" name="license_number" id="license_number" 
                               value="{{ old('license_number', $doctor->license_number) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('license_number') border-red-300 @enderror" 
                               placeholder="Nomor lisensi dokter">
                        @error('license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Experience Years -->
                    <div>
                        <label for="experience_years" class="block text-sm font-medium text-gray-700">Tahun Pengalaman</label>
                        <input type="number" name="experience_years" id="experience_years" min="0" max="50" 
                               value="{{ old('experience_years', $doctor->experience_years) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('experience_years') border-red-300 @enderror" 
                               placeholder="Contoh: 5">
                        @error('experience_years')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Education -->
                    <div>
                        <label for="education" class="block text-sm font-medium text-gray-700">Pendidikan</label>
                        <textarea name="education" id="education" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('education') border-red-300 @enderror" 
                                  placeholder="Pendidikan terakhir">{{ old('education', $doctor->education) }}</textarea>
                        @error('education')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('status') border-red-300 @enderror">
                            <option value="active" {{ old('status', $doctor->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $doctor->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('doctors.show', $doctor) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection