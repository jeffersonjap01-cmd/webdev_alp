@extends('layouts.main')

@section('title', 'Pets - VetCare')

@section('page-title', 'Hewan Peliharaan')
@section('page-description', 'Kelola data hewan peliharaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hewan Peliharaan</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola data hewan peliharaan
            </p>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'owner']))
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('pets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Hewan
            </a>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('pets') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Jenis Hewan</label>
                    <select name="type" id="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Jenis</option>
                        <option value="Dog" {{ request('type') == 'Dog' ? 'selected' : '' }}>Anjing</option>
                        <option value="Cat" {{ request('type') == 'Cat' ? 'selected' : '' }}>Kucing</option>
                        <option value="Bird" {{ request('type') == 'Bird' ? 'selected' : '' }}>Burung</option>
                        <option value="Rabbit" {{ request('type') == 'Rabbit' ? 'selected' : '' }}>Kelinci</option>
                        <option value="Hamster" {{ request('type') == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                        <option value="Fish" {{ request('type') == 'Fish' ? 'selected' : '' }}>Ikan</option>
                        <option value="Other" {{ request('type') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                
                <div>
                    <label for="owner" class="block text-sm font-medium text-gray-700">Pemilik</label>
                    <select name="owner" id="owner" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Pemilik</option>
                        @foreach(\App\Models\Owner::active()->get() as $owner)
                            <option value="{{ $owner->id }}" {{ request('owner') == $owner->id ? 'selected' : '' }}>
                                {{ $owner->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Cari nama atau breed"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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

    <!-- Pets List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($pets ?? [] as $pet)
            <li>
                <a href="{{ route('pets.show', $pet) }}" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-paw text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-blue-600 truncate">
                                            {{ $pet->name }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @switch($pet->type)
                                                    @case('Dog') bg-yellow-100 text-yellow-800 @break
                                                    @case('Cat') bg-green-100 text-green-800 @break
                                                    @case('Bird') bg-blue-100 text-blue-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch
                                            ">
                                                {{ $pet->type }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-tag mr-1.5"></i>
                                                {{ $pet->breed ?? 'Unknown Breed' }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <i class="fas fa-user mr-1.5"></i>
                                                {{ $pet->owner->name ?? 'Unknown Owner' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">
                                    {{ $pet->age ?? 'Unknown' }} tahun
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $pet->weight ?? 'Unknown' }} kg
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-paw text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada hewan peliharaan</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan hewan peliharaan baru</p>
                @if(in_array(auth()->user()->role, ['admin', 'owner']))
                <div class="mt-4">
                    <a href="{{ route('pets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Hewan Pertama
                    </a>
                </div>
                @endif
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($pets) && $pets->hasPages())
    <div class="mt-6">
        {{ $pets->links() }}
    </div>
    @endif
</div>
@endsection