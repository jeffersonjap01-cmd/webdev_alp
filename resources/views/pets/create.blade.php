@extends('layouts.main')

@section('title', 'Tambah Hewan Peliharaan - VetCare')

@section('page-title', 'Tambah Hewan Peliharaan')
@section('page-description', 'Tambahkan hewan peliharaan baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('pets') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Hewan Peliharaan</h1>
                <p class="text-sm text-gray-600">Masukkan data hewan peliharaan baru</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('pets.store') }}" method="POST" class="px-6 py-4">
            @csrf
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Hewan *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror"
                           placeholder="Masukkan nama hewan">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Species -->
                <div>
                    <label for="species" class="block text-sm font-medium text-gray-700">Jenis Hewan *</label>
                    <select name="species" id="species" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('species') border-red-300 @enderror">
                        <option value="">Pilih jenis hewan</option>
                        <option value="Dog" {{ old('species') == 'Dog' ? 'selected' : '' }}>Anjing</option>
                        <option value="Cat" {{ old('species') == 'Cat' ? 'selected' : '' }}>Kucing</option>
                        <option value="Bird" {{ old('species') == 'Bird' ? 'selected' : '' }}>Burung</option>
                        <option value="Rabbit" {{ old('species') == 'Rabbit' ? 'selected' : '' }}>Kelinci</option>
                        <option value="Hamster" {{ old('species') == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                        <option value="Fish" {{ old('species') == 'Fish' ? 'selected' : '' }}>Ikan</option>
                        <option value="Other" {{ old('species') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('species')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Breed -->
                <div>
                    <label for="breed" class="block text-sm font-medium text-gray-700">Ras</label>
                    <input type="text" name="breed" id="breed" value="{{ old('breed') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('breed') border-red-300 @enderror"
                           placeholder="Contoh: Golden Retriever">
                    @error('breed')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700">Umur (tahun)</label>
                    <input type="number" name="age" id="age" value="{{ old('age') }}" min="0" max="50" step="0.1"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('age') border-red-300 @enderror"
                           placeholder="Contoh: 2.5">
                    @error('age')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700">Berat Badan (kg)</label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight') }}" min="0" max="200" step="0.1"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('weight') border-red-300 @enderror"
                           placeholder="Contoh: 15.5">
                    @error('weight')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <select name="gender" id="gender"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('gender') border-red-300 @enderror">
                        <option value="">Pilih jenis kelamin</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Jantan</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Betina</option>
                        <option value="unknown" {{ old('gender') == 'unknown' ? 'selected' : '' }}>Tidak diketahui</option>
                    </select>
                    @error('gender')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
                    <input type="text" name="color" id="color" value="{{ old('color') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('color') border-red-300 @enderror"
                           placeholder="Contoh: Coklat, Hitam, Putih">
                    @error('color')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('pets') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Hewan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection