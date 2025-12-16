@extends('layouts.main')

@section('title', 'Edit Profil')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-4xl mx-auto">

        {{-- PAGE HEADER --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Edit Profil</h1>
            <p class="text-sm text-slate-500">
                Perbarui informasi akun Anda
            </p>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">

            {{-- CARD HEADER --}}
            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-white rounded-t-xl">
                <h2 class="text-lg font-semibold text-slate-800">
                    Informasi Akun
                </h2>
            </div>

            {{-- CARD BODY --}}
            <div class="p-6">

                @if(session('success'))
                    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- NAME --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Nama
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Password Baru
                        </label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Kosongkan jika tidak ingin mengubah"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD CONFIRM --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Konfirmasi Password
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                        <a href="{{ route('profile') }}"
                           class="px-4 py-2 rounded-lg border border-slate-300 text-sm text-slate-700
                                  hover:bg-slate-100 transition">
                            Batal
                        </a>

                        <button type="submit"
                                class="px-5 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold
                                       hover:bg-blue-700 transition shadow-sm">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
