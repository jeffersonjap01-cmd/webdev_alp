@extends('layouts.main')

@section('title', 'Owner Details - VetCare')

@section('page-title', 'Detail Pemilik')
@section('page-description', 'Informasi lengkap pemilik hewan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <img class="h-16 w-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($owner->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $owner->name }}">
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            {{ $owner->name }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-envelope mr-1.5"></i>
                                {{ $owner->email }}
                            </div>
                            @if($owner->phone)
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-phone mr-1.5"></i>
                                {{ $owner->phone }}
                            </div>
                            @endif
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1.5"></i>
                                Bergabung: {{ $owner->registered_date ? $owner->registered_date->format('d M Y') : 'Unknown' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('owners') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('owners.edit', $owner) }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Pribadi</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $owner->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $owner->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Hewan Peliharaan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $owner->pets()->count() }}</dd>
                        </div>
                        @if($owner->address)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $owner->address }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Registrasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $owner->registered_date ? $owner->registered_date->format('d M Y') : 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $owner->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Pets -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Hewan Peliharaan</h3>
                        <a href="{{ route('pets.customer', $owner->id) }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($owner->pets && $owner->pets->count() > 0)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($owner->pets->take(4) as $pet)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-paw text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $pet->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $pet->type }} - {{ $pet->breed }}</p>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-between text-xs text-gray-500">
                                <span>Umur: {{ $pet->age ?? 'Unknown' }} tahun</span>
                                <span>Berat: {{ $pet->weight ?? 'Unknown' }} kg</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($owner->pets->count() > 4)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">Dan {{ $owner->pets->count() - 4 }} hewan peliharaan lainnya</p>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-paw text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada hewan peliharaan</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Janji Temu Terbaru</h3>
                        <a href="{{ route('appointments') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($owner->pets)
                        @php
                            $recentAppointments = \App\Models\Appointment::with(['pet', 'doctor'])
                                ->whereHas('pet', function($q) use ($owner) {
                                    $q->where('customer_id', $owner->id);
                                })
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        
                        @if($recentAppointments->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentAppointments as $appointment)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-blue-600 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $appointment->pet->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $appointment->service_type }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M') }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada janji temu</p>
                        </div>
                        @endif
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-info-circle text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Data tidak tersedia</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statistik Cepat</h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Total Hewan</dt>
                            <dd class="text-sm text-gray-900">{{ $owner->pets()->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Total Janji Temu</dt>
                            <dd class="text-sm text-gray-900">
                                @if($owner->pets)
                                    {{ \App\Models\Appointment::whereHas('pet', function($q) use ($owner) {
                                        $q->where('customer_id', $owner->id);
                                    })->count() }}
                                @else
                                    0
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Janji Temu Bulan Ini</dt>
                            <dd class="text-sm text-gray-900">
                                @if($owner->pets)
                                    {{ \App\Models\Appointment::whereHas('pet', function($q) use ($owner) {
                                        $q->where('customer_id', $owner->id);
                                    })
                                    ->whereMonth('appointment_time', now()->month)
                                    ->whereYear('appointment_time', now()->year)
                                    ->count() }}
                                @else
                                    0
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Contact Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Kontak</h3>
                    <div class="space-y-3">
                        @if($owner->phone)
                        <a href="tel:{{ $owner->phone }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-phone mr-2"></i>
                            Telepon
                        </a>
                        @endif
                        <a href="mailto:{{ $owner->email }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-envelope mr-2"></i>
                            Email
                        </a>
                        @if($owner->address)
                        <a href="https://maps.google.com/?q={{ urlencode($owner->address) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Lihat Alamat
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection