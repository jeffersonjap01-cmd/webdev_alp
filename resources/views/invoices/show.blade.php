@extends('layouts.main')

@section('title', 'Invoice - VetCare')

@section('page-title', 'Invoice')
@section('page-description', 'Detail tagihan pembayaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Invoice #{{ $invoice->invoice_number }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Tanggal: {{ $invoice->date->format('d F Y') }}
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
                <a href="{{ route('invoices') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Invoice Details -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Detail Tagihan</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pasien</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->pet->name ?? 'Unknown' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Pemilik</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $invoice->user->name ?? 'Unknown' }}</dd>
                </div>
                @if($invoice->appointment)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Janji Temu</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $invoice->appointment->appointment_time ? \Carbon\Carbon::parse($invoice->appointment->appointment_time)->format('d F Y, H:i') : '-' }}
                    </dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </dd>
                </div>
                
                <hr class="border-gray-200">
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Subtotal</span>
                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($invoice->tax > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Pajak (11%)</span>
                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($invoice->discount > 0)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Diskon</span>
                        <span class="text-sm font-medium text-gray-900">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <span class="text-base font-semibold text-gray-900">Total</span>
                        <span class="text-base font-bold text-gray-900">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Pembayaran</h3>
            </div>
            <div class="p-6">
                @if($invoice->status === 'paid')
                    <div class="text-center py-8">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                            <i class="fas fa-check-circle text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Pembayaran Lunas</h3>
                        <p class="text-sm text-gray-500">
                            Dibayar pada: {{ $invoice->paid_at ? $invoice->paid_at->format('d F Y, H:i') : '-' }}
                        </p>
                        @if($invoice->payment_method)
                        <p class="text-sm text-gray-500 mt-1">
                            Metode: {{ strtoupper($invoice->payment_method) }}
                        </p>
                        @endif
                    </div>
                @else
                    <!-- QR Code Display -->
                    <div class="text-center mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-4">Scan QR Code untuk Pembayaran</p>
                        <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
                            <img src="{{ asset('images/QRIS.jpg') }}" alt="QR Code QRIS" class="w-64 h-64 mx-auto object-contain">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">QRIS - QR Code Standar Pembayaran Nasional</p>
                        <p class="text-xs text-gray-600 mt-1 font-semibold">Total: Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                    </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <form action="{{ route('invoices.approve-payment', $invoice) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-check mr-2"></i>
                                Approve Pembayaran
                            </button>
                        </form>
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Link to Medical Record -->
    @php
        $medicalRecord = null;
        if($invoice->appointment) {
            $medicalRecord = \App\Models\MedicalRecord::where('appointment_id', $invoice->appointment->id)->first();
        }
    @endphp
    @if($medicalRecord)
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Rekam Medis Terkait</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($invoice->status === 'paid')
                            Rekam medis sudah dapat diakses setelah pembayaran diterima.
                        @else
                            Rekam medis akan dapat diakses setelah pembayaran diterima.
                        @endif
                    </p>
                </div>
                @if($invoice->status === 'paid')
                <a href="{{ route('medical-records.show', $medicalRecord) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-file-medical mr-2"></i>
                    Lihat Rekam Medis
                </a>
                @else
                <button disabled class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i>
                    Belum Dapat Diakses
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

