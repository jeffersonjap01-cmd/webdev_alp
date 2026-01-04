@extends('layouts.main')

@section('title', 'Prescription Details - VetCare')

@section('page-title', 'Detail Resep Obat')
@section('page-description', 'Informasi lengkap resep obat')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-pills text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Resep Obat - {{ $prescription->medication->name ?? 'Unknown Medication' }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-paw mr-1.5"></i>
                                {{ $prescription->pet->name ?? 'Unknown Pet' }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1.5"></i>
                                {{ $prescription->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    @switch($prescription->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('filled') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($prescription->status)
                                        @case('pending') Pending @break
                                        @case('filled') Diisi @break
                                        @case('cancelled') Dibatalkan @break
                                        @default {{ ucfirst($prescription->status) }}
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('prescriptions') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'vet']))
                <a href="{{ route('prescriptions.edit', $prescription) }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
            <!-- Prescription Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detail Resep</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Obat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->medication->name ?? 'Unknown Medication' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    @switch($prescription->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('filled') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($prescription->status)
                                        @case('pending') Pending @break
                                        @case('filled') Diisi @break
                                        @case('cancelled') Dibatalkan @break
                                        @default {{ ucfirst($prescription->status) }}
                                    @endswitch
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dosage</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->dosage }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Frekuensi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->frequency }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Durasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->duration }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if($prescription->instructions)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Instruksi Penggunaan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->instructions }}</dd>
                        </div>
                        @endif
                        @if($prescription->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Medical Record -->
            @if($prescription->medicalRecord)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Rekam Medis</h3>
                        <a href="{{ route('medical-records.show', $prescription->medicalRecord) }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Detail
                        </a>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900">{{ $prescription->medicalRecord->diagnosis ?? 'No Diagnosis' }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $prescription->medicalRecord->treatment ?? 'No Treatment' }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $prescription->medicalRecord->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Appointment -->
            @if($prescription->appointment)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Janji Temu</h3>
                        <a href="{{ route('appointments.show', $prescription->appointment) }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Detail
                        </a>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $prescription->appointment->service_type }}</h4>
                                <p class="text-sm text-gray-500">{{ $prescription->appointment->doctor->name ?? 'Unknown Doctor' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($prescription->appointment->appointment_time)->format('d M Y') }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($prescription->appointment->appointment_time)->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pet Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Hewan</h3>
                    <dl class="space-y-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-paw text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">{{ $prescription->pet->name ?? 'Unknown' }}</dt>
                                <dd class="text-sm text-gray-500">{{ $prescription->pet->type ?? 'Unknown Type' }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Breed</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->pet->breed ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Umur</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->pet->age ?? 'Unknown' }} tahun</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Berat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->pet->weight ?? 'Unknown' }} kg</dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('pets.show', $prescription->pet) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-paw mr-2"></i>
                            Lihat Profil Hewan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Customer</h3>
                    <dl class="space-y-4">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($prescription->pet->customer->name ?? 'Unknown') }}&color=7F9CF5&background=EBF4FF" alt="{{ $prescription->pet->customer->name ?? 'Unknown' }}">
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">{{ $prescription->pet->customer->name ?? 'Unknown' }}</dt>
                                <dd class="text-sm text-gray-500">{{ $prescription->pet->customer->email ?? 'Unknown' }}</dd>
                            </div>
                        </div>
                        @if($prescription->pet->customer && $prescription->pet->customer->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $prescription->pet->customer->phone }}</dd>
                        </div>
                        @endif
                    </dl>
                    @if(optional($prescription->pet)->customer)
                    <div class="mt-4">
                        <a href="{{ route('customers.show', optional($prescription->pet->customer)->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i>
                            Lihat Profil Pemilik
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            @if(in_array(auth()->user()->role, ['admin', 'vet']))
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        @if($prescription->status === 'pending')
                        <button onclick="updateStatus('filled')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>
                            Tandai Diisi
                        </button>
                        @endif
                        @if($prescription->status !== 'cancelled')
                        <button onclick="updateStatus('cancelled')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>
                            Batalkan Resep
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Apakah Anda yakin ingin mengubah status resep ini?')) {
        fetch(`/prescriptions/{{ $prescription->id }}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memperbarui status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui status');
        });
    }
}
</script>
@endsection