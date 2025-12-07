@extends('layouts.main')

@section('title', 'Edit Pet - VetCare')

@section('page-title', 'Edit Hewan Peliharaan')
@section('page-description', 'Edit informasi hewan peliharaan')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Edit Hewan Peliharaan - {{ $pet->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Edit informasi hewan peliharaan
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('pets.update', $pet) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Pet Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Hewan</h3>
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Hewan *</label>
                        <input type="text" name="name" id="name" required 
                               value="{{ old('name', $pet->name) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror" 
                               placeholder="Masukkan nama hewan">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Jenis Hewan *</label>
                        <select name="type" id="type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('type') border-red-300 @enderror">
                            <option value="">Pilih Jenis Hewan</option>
                            <option value="Dog" {{ old('type', $pet->type) == 'Dog' ? 'selected' : '' }}>Anjing</option>
                            <option value="Cat" {{ old('type', $pet->type) == 'Cat' ? 'selected' : '' }}>Kucing</option>
                            <option value="Bird" {{ old('type', $pet->type) == 'Bird' ? 'selected' : '' }}>Burung</option>
                            <option value="Rabbit" {{ old('type', $pet->type) == 'Rabbit' ? 'selected' : '' }}>Kelinci</option>
                            <option value="Hamster" {{ old('type', $pet->type) == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                            <option value="Fish" {{ old('type', $pet->type) == 'Fish' ? 'selected' : '' }}>Ikan</option>
                            <option value="Other" {{ old('type', $pet->type) == 'Other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Breed -->
                    <div>
                        <label for="breed" class="block text-sm font-medium text-gray-700">Breed/Ras</label>
                        <input type="text" name="breed" id="breed" 
                               value="{{ old('breed', $pet->breed) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('breed') border-red-300 @enderror" 
                               placeholder="Contoh: Golden Retriever, Persian, dll">
                        @error('breed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Age and Weight -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700">Umur (tahun)</label>
                            <input type="number" name="age" id="age" min="0" max="50" 
                                   value="{{ old('age', $pet->age) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('age') border-red-300 @enderror" 
                                   placeholder="Contoh: 2">
                            @error('age')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                            <input type="number" name="weight" id="weight" min="0" step="0.1" 
                                   value="{{ old('weight', $pet->weight) }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('weight') border-red-300 @enderror" 
                                   placeholder="Contoh: 15.5">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('gender') border-red-300 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="male" {{ old('gender', $pet->gender) == 'male' ? 'selected' : '' }}>Jantan</option>
                            <option value="female" {{ old('gender', $pet->gender) == 'female' ? 'selected' : '' }}>Betina</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
                        <input type="text" name="color" id="color" 
                               value="{{ old('color', $pet->color) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('color') border-red-300 @enderror" 
                               placeholder="Contoh: Coklat, Hitam, Putih">
                        @error('color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birth Date -->
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date" 
                               value="{{ old('birth_date', $pet->birth_date ? $pet->birth_date->format('Y-m-d') : '') }}" 
                               max="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('birth_date') border-red-300 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Microchip ID -->
                    <div>
                        <label for="microchip_id" class="block text-sm font-medium text-gray-700">ID Microchip</label>
                        <input type="text" name="microchip_id" id="microchip_id" 
                               value="{{ old('microchip_id', $pet->microchip_id) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('microchip_id') border-red-300 @enderror" 
                               placeholder="Nomor microchip (opsional)">
                        @error('microchip_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('status') border-red-300 @enderror">
                            <option value="active" {{ old('status', $pet->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $pet->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-300 @enderror" 
                                  placeholder="Catatan tambahan tentang hewan">{{ old('notes', $pet->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('pets.show', $pet) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Update Hewan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection