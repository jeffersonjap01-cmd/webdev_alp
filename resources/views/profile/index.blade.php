@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
                <div class="d-none d-sm-inline-block">
                    <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'vet' ? 'info' : 'success') }} p-2">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Basic Information Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Dasar</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        @if($user->role === 'vet' && isset($profileData['doctor_info']['photo_url']) && $profileData['doctor_info']['photo_url'])
                            <img class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" 
                                 src="{{ $profileData['doctor_info']['photo_url'] }}" alt="Foto Profil">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 150px; height: 150px;">
                                <span class="text-white h1">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h5 class="font-weight-bold">{{ $profileData['basic_info']['name'] }}</h5>
                        <p class="text-muted">{{ $profileData['basic_info']['email'] }}</p>
                        <p class="text-muted small">Anggota sejak: {{ $profileData['basic_info']['member_since'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Owner Specific Info -->
            @if($user->role === 'owner' && isset($profileData['owner_info']))
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Kontak</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4"><strong>Telepon:</strong></div>
                            <div class="col-sm-8">{{ $profileData['owner_info']['phone'] ?? 'Tidak tersedia' }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4"><strong>Alamat:</strong></div>
                            <div class="col-sm-8">{{ $profileData['owner_info']['address'] ?? 'Tidak tersedia' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Doctor Specific Info -->
            @if($user->role === 'vet' && isset($profileData['doctor_info']))
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Profesional</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5"><strong>Telepon:</strong></div>
                            <div class="col-sm-7">{{ $profileData['doctor_info']['phone'] ?? 'Tidak tersedia' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5"><strong>Spesialisasi:</strong></div>
                            <div class="col-sm-7">{{ $profileData['doctor_info']['specialization'] ?? 'Tidak tersedia' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5"><strong>Status:</strong></div>
                            <div class="col-sm-7">
                                <span class="badge badge-{{ $profileData['doctor_info']['status'] === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($profileData['doctor_info']['status']) }}
                                </span>
                            </div>
                        </div>
                        @if($profileData['doctor_info']['bio'])
                            <div class="row">
                                <div class="col-sm-12"><strong>Bio:</strong></div>
                                <div class="col-sm-12 mt-2">{{ $profileData['doctor_info']['bio'] }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Role-specific Information -->
        <div class="col-xl-8 col-lg-7">
            <!-- Owner Dashboard -->
            @if($user->role === 'owner' && isset($profileData['owner_info']))
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Hewan Peliharaan
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['owner_info']['total_pets'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-paw fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Janji Temu
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['owner_info']['total_appointments'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Tagihan
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['owner_info']['total_invoices'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Terdaftar Sejak
                                        </div>
                                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['owner_info']['registered_date'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Doctor Dashboard -->
            @if($user->role === 'vet' && isset($profileData['doctor_info']))
                <div class="row">
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Janji Temu
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['doctor_info']['total_appointments'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Rekam Medis
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['doctor_info']['total_medical_records'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Resep Obat
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['doctor_info']['total_prescriptions'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Admin Dashboard -->
            @if($user->role === 'admin' && isset($profileData['admin_info']))
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Users
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['admin_info']['total_users'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Doctors
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['admin_info']['total_doctors'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-md fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Total Owners
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['admin_info']['total_owners'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Total Appointments
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $profileData['admin_info']['total_appointments'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($user->role === 'owner')
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('pets.create') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-plus"></i> Tambah Hewan Peliharaan
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('appointments.create') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-calendar-plus"></i> Buat Janji Temu
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('pets.index') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-paw"></i> Lihat Hewan Peliharaan
                                </a>
                            </div>
                        @elseif($user->role === 'vet')
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('medical-records.create') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-file-medical"></i> Buat Rekam Medis
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('prescriptions.create') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-pills"></i> Buat Resep Obat
                                </a>
                            </div>
                        @elseif($user->role === 'admin')
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('owners.create') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-user-plus"></i> Tambah Owner
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('doctors.create') }}" class="btn btn-success btn-block">
                                    <i class="fas fa-user-md"></i> Tambah Doctor
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('reports.dashboard') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection