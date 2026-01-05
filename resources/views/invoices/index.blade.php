@extends('layouts.main')

@section('title', 'Invoices - VetCare')

@section('page-title', 'Tagihan')
@section('page-description', 'Daftar tagihan pembayaran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tagihan</h1>
            <p class="mt-1 text-sm text-gray-600">
                Daftar tagihan pembayaran
            </p>
        </div>
    </div>

    <!-- Invoices List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($invoices ?? [] as $invoice)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('invoices.show', $invoice) }}" class="flex items-center hover:bg-gray-50 flex-1">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100' : 'bg-yellow-100' }} flex items-center justify-center">
                                    <i class="fas fa-receipt {{ $invoice->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $invoice->invoice_number }}
                                    </p>
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-paw mr-1.5"></i>
                                            {{ $invoice->pet->name ?? 'Unknown Pet' }}
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                            <i class="fas fa-calendar mr-1.5"></i>
                                            {{ $invoice->date->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm font-semibold text-gray-900 sm:mt-0">
                                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="ml-4 flex-shrink-0">
                            <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="px-4 py-12 text-center">
                <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada tagihan</p>
            </li>
            @endforelse
        </ul>
    </div>

    @if(isset($invoices) && $invoices->hasPages())
    <div class="mt-6">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection

