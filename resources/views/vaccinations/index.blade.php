@extends('layouts.main')

@section('title', 'Vaccinations - VetCare')

@section('page-title', 'Vaksinasi')
@section('page-description', 'Kelola jadwal dan riwayat vaksinasi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vaksinasi</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola jadwal dan riwayat vaksinasi
            </p>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'vet']))
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('vaccinations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Vaksinasi
            </a>
        </div>
        @endif
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('vaccinations') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Semua Vaksinasi
            </a>
            <a href="{{ route('vaccinations.upcoming') }}" class="border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Janji Temu Mendatang
            </a>
        </nav>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('vaccinations') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label for="vaccine_type" class="block text-sm font-medium text-gray-700">Jenis Vaccine</label>
                    <select name="vaccine_type" id="vaccine_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Vaccine</option>
                        <option value="Rabies" {{ request('vaccine_type') == 'Rabies' ? 'selected' : '' }}>Rabies</option>
                        <option value="DHPP" {{ request('vaccine_type') == 'DHPP' ? 'selected' : '' }}>DHPP</option>
                        <option value="Bordetella" {{ request('vaccine_type') == 'Bordetella' ? 'selected' : '' }}>Bordetella</option>
                        <option value="FVRCP" {{ request('vaccine_type') == 'FVRCP' ? 'selected' : '' }}>FVRCP</option>
                        <option value="FeLV" {{ request('vaccine_type') == 'FeLV' ? 'selected' : '' }}>FeLV</option>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Status</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                
                <div>
                    <label for="pet" class="block text-sm font-medium text-gray-700">Hewan</label>
                    <select name="pet" id="pet" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Hewan</option>
                        @foreach(\App\Models\Pet::with('owner')->get() as $pet)
                            <option value="{{ $pet->id }}" {{ request('pet') == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }} - {{ $pet->owner->name ?? 'Unknown Owner' }}
                            </option>
                        @endforeach
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

    <!-- Vaccinations List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($vaccinations ?? [] as $vaccination)
            <li>
                <a href="{{ route('vaccinations.show', $vaccination) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-syringe text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $vaccination->pet->name ?? 'Unknown Pet' }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @switch($vaccination->status)
                                                    @case('scheduled') bg-yellow-100 text-yellow-800 @break
                                                    @case('completed') bg-green-100 text-green-800 @break
                                                    @case('overdue') bg-red-100 text-red-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch
                                            ">
                                                @switch($vaccination->status)
                                                    @case('scheduled') Terjadwal @break
                                                    @case('completed') Selesai @break
                                                    @case('overdue') Terlambat @break
                                                    @default {{ ucfirst($vaccination->status) }}
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-pills mr-1.5"></i>
                                                {{ $vaccination->vaccine_type }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-calendar mr-1.5"></i>
                                                {{ $vaccination->scheduled_date ? $vaccination->scheduled_date->format('d M Y') : 'No Date' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    {{ $vaccination->pet->owner->name ?? 'Unknown Owner' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $vaccination->pet->type ?? 'Unknown Type' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-syringe text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada vaksinasi</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan jadwal vaksinasi baru</p>
                @if(in_array(auth()->user()->role, ['admin', 'vet']))
                <div class="mt-4">
                    <a href="{{ route('vaccinations.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Vaksinasi Pertama
                    </a>
                </div>
                @endif
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($vaccinations) && $vaccinations->hasPages())
    <div class="mt-6">
        {{ $vaccinations->links() }}
    </div>
    @endif
</div>
@endsection