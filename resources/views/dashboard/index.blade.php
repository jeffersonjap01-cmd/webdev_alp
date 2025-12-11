@extends('layouts.main')

@section('title', 'Dashboard - VetCare')

@section('page-title', 'Dashboard')

@section('page-description', 'Selamat datang di sistem manajemen VetCare')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section with Banner -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-xl overflow-hidden mb-8">
        <div class="absolute inset-0">
            <img src="{{ asset('images/banner.png') }}" alt="VetCare Banner" class="w-full h-full object-cover opacity-20">
        </div>
        <div class="relative px-6 py-12 sm:px-12 sm:py-16">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Selamat Datang, {{ auth()->user()->name }}!
                </h1>
                <p class="text-lg text-blue-100 mb-6">
                    @switch(auth()->user()->role)
                        @case('admin')
                            Kelola sistem klinik hewan dengan mudah dan efisien
                            @break
                        @case('dokter')
                            Berikan perawatan terbaik untuk pasien Anda hari ini
                            @break
                        @case('customer')
                            Kelola kesehatan hewan peliharaan Anda dengan mudah
                            @break
                    @endswitch
                </p>
                <div class="flex flex-wrap gap-3">
                    @if(in_array(auth()->user()->role, ['customer', 'admin']))
                    <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Appointment
                    </a>
                    <a href="{{ route('pets.index') }}" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        <i class="fas fa-paw mr-2"></i>
                        Lihat Hewan Saya
                    </a>
                    @endif
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('appointments') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Kelola Appointments
                    </a>
                    <a href="{{ route('doctors') }}" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        <i class="fas fa-user-md mr-2"></i>
                        Kelola Dokter
                    </a>
                    @endif
                    @if(auth()->user()->role === 'dokter')
                    <a href="{{ route('appointments') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Jadwal Saya
                    </a>
                    <a href="{{ route('medical-records') }}" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                        <i class="fas fa-file-medical mr-2"></i>
                        Rekam Medis
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Dashboard Summary</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if(auth()->user()->role === 'admin')
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Total Customers',
                        'value' => number_format($stats['total_users'] ?? 0),
                        'icon' => 'users',
                        'color' => 'blue'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Total Pets',
                        'value' => number_format($stats['total_pets'] ?? 0),
                        'icon' => 'paw',
                        'color' => 'green'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Active Doctors',
                        'value' => number_format($stats['total_doctors'] ?? 0),
                        'icon' => 'user-md',
                        'color' => 'purple'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Today Appointments',
                        'value' => number_format($stats['today_appointments'] ?? 0),
                        'icon' => 'calendar-alt',
                        'color' => 'yellow'
                    ])
                @elseif(auth()->user()->role === 'dokter')
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Today Appointments',
                        'value' => number_format($stats['today_appointments'] ?? 0),
                        'icon' => 'calendar-day',
                        'color' => 'blue'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Total Patients',
                        'value' => number_format($stats['total_patients'] ?? 0),
                        'icon' => 'paw',
                        'color' => 'green'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Upcoming Appointments',
                        'value' => number_format($stats['upcoming_appointments'] ?? 0),
                        'icon' => 'calendar-alt',
                        'color' => 'purple'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Recent Records',
                        'value' => number_format($stats['recent_medical_records'] ?? 0),
                        'icon' => 'file-medical',
                        'color' => 'yellow'
                    ])
                @elseif(auth()->user()->role === 'customer')
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'My Pets',
                        'value' => number_format($stats['total_pets'] ?? 0),
                        'icon' => 'paw',
                        'color' => 'blue'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Upcoming Appointments',
                        'value' => number_format($stats['upcoming_appointments'] ?? 0),
                        'icon' => 'calendar-check',
                        'color' => 'green'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Upcoming Vaccinations',
                        'value' => number_format($stats['upcoming_vaccinations'] ?? 0),
                        'icon' => 'syringe',
                        'color' => 'purple'
                    ])
                    @include('dashboard.widgets.stats-card', [
                        'title' => 'Pending Payments',
                        'value' => number_format($stats['pending_payments'] ?? 0),
                        'icon' => 'credit-card',
                        'color' => 'yellow'
                    ])
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    @if(auth()->user()->role === 'admin' && isset($stats['recent_activities']) && count($stats['recent_activities']) > 0)
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-clock mr-2 text-gray-400"></i>
                Aktivitas Terbaru
            </h3>
            <div class="flow-root">
                <ul class="-mb-8">
                    @foreach($stats['recent_activities'] as $index => $activity)
                    <li class="relative {{ $loop->last ? '' : 'pb-8' }}">
                        @if(!$loop->last)
                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full bg-{{ $activity['color'] ?? 'gray' }}-500 flex items-center justify-center ring-8 ring-white">
                                    <i class="fas fa-{{ $activity['icon'] ?? 'circle' }} text-white text-xs"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        {!! $activity['message'] ?? '' !!}
                                    </p>
                                </div>
                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                    {{ $activity['time']->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection