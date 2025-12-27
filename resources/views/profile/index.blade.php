@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')

<div class="p-6 space-y-6">

```
<!-- HERO SECTION -->
<div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl shadow text-white p-8 flex flex-col md:flex-row justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold mb-1">Welcome back, {{ $user->name }} 👋</h1>
        <p class="text-white/80">Kelola aktivitas dan informasi akun Anda di VetCare</p>
        <span class="inline-block mt-3 px-4 py-1 rounded-full text-sm font-semibold bg-white text-blue-600">
            {{ ucfirst($user->role) }}
        </span>

        @if(\Illuminate\Support\Facades\Route::has('profile.edit'))
            <a href="{{ route('profile.edit') }}" class="inline-block mt-3 ml-3 px-4 py-1 rounded-full text-sm font-semibold bg-white/95 text-blue-600 shadow-sm hover:bg-white">
                Edit Profile
            </a>
        @else
            <a href="#" class="inline-block mt-3 ml-3 px-4 py-1 rounded-full text-sm font-semibold bg-white/50 text-blue-400 opacity-60 cursor-not-allowed" title="profile.edit route not defined">
                Edit Profile
            </a>
        @endif
    </div>

    <div class="mt-6 md:mt-0">
        @if($user->role === 'doctor' && !empty($profileData['doctor_info']['photo_url']))
            <img src="{{ $profileData['doctor_info']['photo_url'] }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow">
        @else
            <div class="w-32 h-32 rounded-full bg-white/20 flex items-center justify-center text-5xl font-bold">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
        @endif
    </div>
</div>

<!-- STATS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @if($user->role === 'admin')
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-2xl font-bold">{{ $profileData['admin_info']['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Doctors</p>
            <p class="text-2xl font-bold">{{ $profileData['admin_info']['total_doctors'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Appointments</p>
            <p class="text-2xl font-bold">{{ $profileData['admin_info']['total_appointments'] }}</p>
        </div>
    @elseif($user->role === 'customer')
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Hewan</p>
            <p class="text-2xl font-bold">{{ $profileData['customer_info']['total_pets'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Janji</p>
            <p class="text-2xl font-bold">{{ $profileData['customer_info']['total_appointments'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Tagihan</p>
            <p class="text-2xl font-bold">{{ $profileData['customer_info']['total_invoices'] }}</p>
        </div>
    @elseif($user->role === 'doctor')
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Janji</p>
            <p class="text-2xl font-bold">{{ $profileData['doctor_info']['total_appointments'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Rekam Medis</p>
            <p class="text-2xl font-bold">{{ $profileData['doctor_info']['total_medical_records'] }}</p>
        </div>
    @endif
</div>

<!-- DETAIL & ACTIONS -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- DETAIL -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Informasi Akun</h2>
        <ul class="space-y-2 text-gray-600">
            <li><strong>Email:</strong> {{ $profileData['basic_info']['email'] }}</li>
            <li><strong>Role:</strong> {{ ucfirst($user->role) }}</li>
            @if($user->role === 'customer')
                <li><strong>Telepon:</strong> {{ $profileData['customer_info']['phone'] ?? '-' }}</li>
                <li><strong>Alamat:</strong> {{ $profileData['customer_info']['address'] ?? '-' }}</li>
            @endif
            @if($user->role === 'doctor')
                <li><strong>Spesialisasi:</strong> {{ $profileData['doctor_info']['specialization'] ?? '-' }}</li>
            @endif
        </ul>
    </div>

    <!-- ACTIONS -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 gap-4">
            @if($user->role === 'admin')
                <a href="{{ route('doctors.create') }}" class="btn-success">Tambah Doctor</a>
                <a href="{{ route('reports.dashboard') }}" class="btn-info col-span-2">Laporan</a>
            @elseif($user->role === 'customer')
                <a href="{{ route('pets.create') }}" class="btn-primary">Tambah Hewan</a>
                <a href="{{ route('appointments.create') }}" class="btn-success">Buat Janji</a>
            @elseif($user->role === 'doctor')
                <a href="{{ route('medical-records.create') }}" class="btn-primary">Rekam Medis</a>
                <a href="{{ route('prescriptions.create') }}" class="btn-success">Resep</a>
            @endif
        </div>
    </div>
</div>
```

</div>
@endsection
