@extends('layouts.main')

@section('title', 'Dashboard - VetCare')

@section('page-title', 'Dashboard')

@section('page-description', 'Selamat datang di sistem manajemen VetCare')

@section('content')
@section('hero')
<div class="w-full">
    <div class="w-full rounded-b-lg overflow-hidden shadow-lg">
        <div class="relative h-72 sm:h-80 lg:h-96 bg-cover bg-center" style="background-image: url('{{ asset('images/banner.png') }}')">
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="relative z-10 px-6 py-8 sm:px-10 sm:py-12 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="text-white max-w-2xl bg-black/30 backdrop-blur-sm p-4 rounded-lg">
                    <h1 class="text-2xl sm:text-3xl font-extrabold drop-shadow">Selamat datang di VetCare</h1>
                    <p class="mt-2 text-sm sm:text-base text-white/90">Kelola janji temu, rekam medis, dan catatan hewan peliharaan Anda dengan mudah.</p>
                    <div class="mt-4 flex items-center gap-3">
                        <img class="h-12 w-12 rounded-full ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=ffffff&background=4f46e5" alt="{{ auth()->user()->name }}">
                        <div>
                            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-white/90">
                                @switch(auth()->user()->role)
                                    @case('admin') Administrator @break
                                    @case('vet') Dokter Hewan @break
                                    @case('owner') Pemilik Hewan @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 w-full sm:w-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                        <a href="{{ route('dashboard') }}" role="button" aria-label="Overview" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-base font-medium rounded-md bg-white/10 text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                            Overview
                        </a>

                        @if(in_array(auth()->user()->role, ['admin','owner','vet']))
                        <a href="{{ route('appointments') }}" role="button" aria-label="Buat Janji" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-base font-semibold rounded-md shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Buat Janji
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

    <!-- Stats Summary -->
    <div class="mb-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @php $role = auth()->user()->role ?? null; @endphp

            @if(in_array($role, ['admin','vet']))
                @include('dashboard.widgets.stats-card', [
                    'title' => 'Total Users',
                    'value' => isset($stats['total_users']) ? $stats['total_users'] : '1,234',
                    'icon' => 'users',
                    'color' => 'blue',
                    'change' => isset($stats['users_change']) ? $stats['users_change'] : '+12%',
                    'changeType' => 'increase'
                ])
            @endif

            @include('dashboard.widgets.stats-card', [
                'title' => 'Active Appointments',
                'value' => isset($stats['active_appointments']) ? $stats['active_appointments'] : '89',
                'icon' => 'calendar-alt',
                'color' => 'green',
                'change' => isset($stats['appointments_change']) ? $stats['appointments_change'] : '+5%',
                'changeType' => 'increase'
            ])

            @include('dashboard.widgets.stats-card', [
                'title' => 'Pets',
                'value' => isset($stats['pets']) ? $stats['pets'] : '412',
                'icon' => 'paw',
                'color' => 'purple',
                'change' => isset($stats['pets_change']) ? $stats['pets_change'] : '+3%',
                'changeType' => 'increase'
            ])

            @if(in_array($role, ['admin','vet']))
                @include('dashboard.widgets.stats-card', [
                    'title' => 'Invoices',
                    'value' => isset($stats['invoices']) ? $stats['invoices'] : '124',
                    'icon' => 'dollar-sign',
                    'color' => 'yellow',
                    'change' => isset($stats['invoices_change']) ? $stats['invoices_change'] : '-1%',
                    'changeType' => 'decrease'
                ])
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-1">
        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-bolt mr-2 text-gray-400"></i>
                    Aksi Cepat
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @if(in_array(auth()->user()->role, ['admin', 'owner']))
                        <a href="{{ route('appointments') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Lihat Janji
                        </a>
                    @endif
                    
                    @if(in_array(auth()->user()->role, ['admin', 'owner']))
                        <a href="{{ route('pets') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-paw mr-2"></i>
                            Lihat Hewan
                        </a>
                    @endif
                    
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('doctors') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <i class="fas fa-user-md mr-2"></i>
                            Lihat Dokter
                        </a>
                    @endif
                    
                    @if(in_array(auth()->user()->role, ['admin', 'vet']))
                        <a href="{{ route('medical-records') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-file-medical mr-2"></i>
                            Lihat Rekam Medis
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection