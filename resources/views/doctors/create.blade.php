@extends('layouts.main')

@section('title', 'Create Doctor - VetCare')

@section('page-title', 'Tambah Dokter Baru')
@section('page-description', 'Tambahkan dokter hewan baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Tambah Dokter Hewan Baru
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Isi form di bawah untuk menambahkan dokter hewan baru
                </p>
            </div>
            <a href="{{ route('doctors') }}"
               class="px-4 py-2 border rounded-md text-sm bg-white hover:bg-gray-50">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="p-6">
            <form action="{{ route('doctors.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- BASIC INFO -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Informasi Akun Dokter
                    </h3>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium">Nama Lengkap *</label>
                        <input type="text" name="name" required
                               value="{{ old('name') }}"
                               class="mt-1 w-full border rounded-md px-3 py-2">
                        @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium">Email *</label>
                        <input type="email" name="email" required
                               value="{{ old('email') }}"
                               class="mt-1 w-full border rounded-md px-3 py-2">
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium">Password *</label>
                        <input type="password" name="password" required
                               class="mt-1 w-full border rounded-md px-3 py-2">
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label class="block text-sm font-medium">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" required
                               class="mt-1 w-full border rounded-md px-3 py-2">
                    </div>
                </div>

                <!-- DOCTOR PROFILE -->
                <div class="space-y-4 pt-6 border-t">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Profil Dokter
                    </h3>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium">Nomor Telepon</label>
                        <input type="text" name="phone"
                               value="{{ old('phone') }}"
                               class="mt-1 w-full border rounded-md px-3 py-2">
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label class="block text-sm font-medium">Spesialisasi</label>
                        <select name="specialization"
                                class="mt-1 w-full border rounded-md px-3 py-2">
                            <option value="">-- Pilih Spesialisasi --</option>
                            <option value="General Practice">General Practice</option>
                            <option value="Surgery">Surgery</option>
                            <option value="Dermatology">Dermatology</option>
                            <option value="Cardiology">Cardiology</option>
                            <option value="Orthopedics">Orthopedics</option>
                            <option value="Neurology">Neurology</option>
                        </select>
                    </div>


                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status"
                                class="mt-1 w-full border rounded-md px-3 py-2">
                            <option value="inactive" selected>Nonaktif</option>
                            <option value="active">Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="flex justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('doctors') }}"
                       class="px-4 py-2 border rounded-md">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Simpan Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
