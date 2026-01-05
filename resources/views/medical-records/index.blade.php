@extends('layouts.main')

@section('title', 'Medical Records - VetCare')

@section('page-title', 'Rekam Medis')
@section('page-description', 'Kelola rekam medis hewan peliharaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rekam Medis</h1>
            <p class="mt-1 text-sm text-gray-600">
                Kelola rekam medis hewan peliharaan
            </p>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'vet']))
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Rekam Medis
            </a>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form method="GET" action="{{ route('medical-records') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <label for="pet" class="block text-sm font-medium text-gray-700">Hewan</label>
                    <select name="pet" id="pet" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Hewan</option>
                        @foreach(\App\Models\Pet::with('user')->get() as $pet)
                            <option value="{{ $pet->id }}" {{ request('pet') == $pet->id ? 'selected' : '' }}>
                                    {{ $pet->name }} - {{ $pet->user->name ?? 'Unknown Customer' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="doctor" class="block text-sm font-medium text-gray-700">Dokter</label>
                    <select name="doctor" id="doctor" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Semua Dokter</option>
                        @foreach(\App\Models\Doctor::active()->get() as $doctor)
                            <option value="{{ $doctor->id }}" {{ request('doctor') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </form>
        </div>
    </div>

    <!-- Medical Records List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($medicalRecords ?? [] as $record)
            @php
                $invoice = null;
                if($record->appointment) {
                    $invoice = \App\Models\Invoice::where('appointment_id', $record->appointment->id)->first();
                }
                $isPaid = $invoice && $invoice->status === 'paid';
                $isCustomer = auth()->user()->role === 'customer';
            @endphp
            <li>
                <div class="px-4 py-4 sm:px-6 {{ !$isPaid && $isCustomer ? 'bg-red-50 border-l-4 border-red-500' : '' }}">
                    <div class="flex items-center justify-between">
                        @if($isPaid || !$isCustomer)
                        <a href="{{ route('medical-records.show', $record) }}" class="flex items-center hover:bg-gray-50 flex-1 {{ !$isPaid && $isCustomer ? 'opacity-75' : '' }}">
                        @else
                        <div class="flex items-center flex-1 cursor-not-allowed">
                        @endif
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full {{ !$isPaid && $isCustomer ? 'bg-red-200' : 'bg-red-100' }} flex items-center justify-center">
                                    <i class="fas fa-file-medical {{ !$isPaid && $isCustomer ? 'text-red-700' : 'text-red-600' }}"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium {{ !$isPaid && $isCustomer ? 'text-red-700' : 'text-blue-600' }} truncate">
                                        {{ $record->pet->name ?? 'Unknown Pet' }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $record->doctor->name ?? 'Unknown Doctor' }}
                                        </p>
                                        @if($isCustomer && !$isPaid)
                                        <p class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Belum Dibayar
                                        </p>
                                        @elseif($isCustomer && $isPaid)
                                        <p class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Lunas
                                        </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm {{ !$isPaid && $isCustomer ? 'text-red-600' : 'text-gray-500' }}">
                                            <i class="fas fa-diagnosis mr-1.5"></i>
                                            {{ $record->diagnoses->first()->diagnosis_name ?? ($record->diagnosis ?? 'No Diagnosis') }}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm {{ !$isPaid && $isCustomer ? 'text-red-600' : 'text-gray-500' }} sm:mt-0 sm:ml-6">
                                            <i class="fas fa-calendar mr-1.5"></i>
                                            {{ $record->created_at->format('d M Y') }}
                                        </p>
                                        @if($isCustomer && !$isPaid && $invoice)
                                        <p class="mt-2 flex items-center text-sm text-red-700 font-semibold sm:mt-0 sm:ml-6">
                                            <i class="fas fa-money-bill-wave mr-1.5"></i>
                                            Total: Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @if($isPaid || !$isCustomer)
                        </a>
                        @else
                        </div>
                        @endif
                        <div class="ml-4 flex-shrink-0 flex items-center space-x-2">
                            @if($isCustomer && !$isPaid && $invoice)
                            <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center px-3 py-1.5 border border-red-600 rounded-md text-xs font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Bayar Tagihan">
                                <i class="fas fa-credit-card mr-1"></i>
                                Bayar
                            </a>
                            @elseif($isPaid || !$isCustomer)
                            <a href="{{ route('medical-records.export-pdf', $record) }}" class="inline-flex items-center px-3 py-1.5 border border-blue-600 rounded-md text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Export to PDF">
                                <i class="fas fa-file-pdf mr-1"></i>
                                PDF
                            </a>
                            <a href="{{ route('medical-records.show', $record) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="View Details">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-file-medical text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada rekam medis</p>
                <p class="text-gray-400 text-sm">Mulai dengan menambahkan rekam medis baru</p>
                @if(in_array(auth()->user()->role, ['admin', 'vet']))
                <div class="mt-4">
                    <a href="{{ route('medical-records.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Rekam Medis Pertama
                    </a>
                </div>
                @endif
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($medicalRecords) && $medicalRecords->hasPages())
    <div class="mt-6">
        {{ $medicalRecords->links() }}
    </div>
    @endif
</div>
@endsection