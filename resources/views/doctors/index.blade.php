@extends('layouts.main')

@section('title', 'Doctors - VetCare')

@section('page-title', 'Dokter Hewan')
@section('page-description', 'Kelola data dokter hewan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dokter Hewan</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola data dokter hewan
            </p>
        </div>
        @if(auth()->user()->role === 'admin')
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('doctors.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Dokter
            </a>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('doctors') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                    <select name="specialization" id="specialization" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Spesialisasi</option>
                        <option value="General Practice" {{ request('specialization') == 'General Practice' ? 'selected' : '' }}>General Practice</option>
                        <option value="Surgery" {{ request('specialization') == 'Surgery' ? 'selected' : '' }}>Surgery</option>
                        <option value="Dermatology" {{ request('specialization') == 'Dermatology' ? 'selected' : '' }}>Dermatology</option>
                        <option value="Cardiology" {{ request('specialization') == 'Cardiology' ? 'selected' : '' }}>Cardiology</option>
                        <option value="Orthopedics" {{ request('specialization') == 'Orthopedics' ? 'selected' : '' }}>Orthopedics</option>
                        <option value="Oncology" {{ request('specialization') == 'Oncology' ? 'selected' : '' }}>Oncology</option>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Doctors List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($doctors ?? [] as $doctor)
            <li>
                <a href="{{ route('doctors.show', $doctor) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($doctor->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $doctor->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $doctor->name }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $doctor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $doctor->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-stethoscope mr-1.5"></i>
                                                {{ $doctor->specialization ?? 'General Practice' }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-envelope mr-1.5"></i>
                                                {{ $doctor->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    {{ $doctor->appointments ? $doctor->appointments->count() : 0 }} Janji Temu
                                </p>
                                <p class="text-sm text-gray-500">
                                    Bergabung: {{ $doctor->created_at->format('M Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-user-md text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada dokter hewan</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan dokter hewan baru</p>
                @if(auth()->user()->role === 'admin')
                <div class="mt-4">
                    <a href="{{ route('doctors.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Dokter Pertama
                    </a>
                </div>
                @endif
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($doctors) && $doctors->hasPages())
    <div class="mt-6">
        {{ $doctors->links() }}
    </div>
    @endif
</div>
@endsection