@extends('layouts.main')

@section('title', 'Appointment Details - VetCare')

@section('page-title', 'Detail Janji Temu')
@section('page-description', 'Informasi lengkap janji temu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Janji Temu - {{ $appointment->pet->name ?? 'Unknown Pet' }}
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-1.5"></i>
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, H:i') }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="fas fa-user-md mr-1.5"></i>
                        {{ $appointment->doctor->name ?? 'No Doctor Assigned' }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="fas fa-clock mr-1.5"></i>
                        Durasi: {{ $appointment->duration ?? 30 }} menit
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('appointments') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'vet']) && $appointment->status === 'scheduled')
                <button type="button" onclick="updateStatus('confirmed')" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i>
                    Konfirmasi
                </button>
                @endif
                @if(in_array(auth()->user()->role, ['admin', 'vet']) && in_array($appointment->status, ['scheduled', 'confirmed']))
                <button type="button" onclick="updateStatus('completed')" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-check-circle mr-2"></i>
                    Selesai
                </button>
                @endif
                @if(in_array(auth()->user()->role, ['admin']) && in_array($appointment->status, ['scheduled', 'confirmed']))
                <button type="button" onclick="updateStatus('cancelled')" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i>
                    Batalkan
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Appointment Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detail Janji Temu</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @switch($appointment->status)
                                        @case('scheduled') bg-yellow-100 text-yellow-800 @break
                                        @case('confirmed') bg-green-100 text-green-800 @break
                                        @case('completed') bg-blue-100 text-blue-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($appointment->status)
                                        @case('scheduled') Terjadwal @break
                                        @case('confirmed') Dikonfirmasi @break
                                        @case('completed') Selesai @break
                                        @case('cancelled') Dibatalkan @break
                                        @default {{ ucfirst($appointment->status) }}
                                    @endswitch
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Layanan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->service_type ?? 'General Checkup' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->updated_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if($appointment->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Medical Records -->
            @if($appointment->medicalRecords && $appointment->medicalRecords->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Rekam Medis</h3>
                    <div class="space-y-4">
                        @foreach($appointment->medicalRecords as $record)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $record->diagnosis ?? 'No Diagnosis' }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $record->treatment ?? 'No Treatment' }}</p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $record->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Prescriptions -->
            @if($appointment->prescriptions && $appointment->prescriptions->count() > 0)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Resep Obat</h3>
                    <div class="space-y-4">
                        @foreach($appointment->prescriptions as $prescription)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $prescription->medication->name ?? 'Unknown Medication' }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $prescription->dosage }}</p>
                                    <p class="text-sm text-gray-500">{{ $prescription->frequency }} - {{ $prescription->duration }}</p>
                                </div>
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
                        @endforeach
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
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->name ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->type ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Breed</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->breed ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Umur</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->age ?? 'Unknown' }} tahun</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Berat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->weight ?? 'Unknown' }} kg</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Pemilik</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->owner->name ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->owner->email ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->owner->phone ?? 'Unknown' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Doctor Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Dokter</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->doctor->name ?? 'Not Assigned' }}</dd>
                        </div>
                        @if($appointment->doctor)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Spesialisasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->doctor->specialization ?? 'General Practice' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $appointment->doctor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($appointment->doctor->status ?? 'inactive') }}
                                </span>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStatus(status) {
    if (confirm('Apakah Anda yakin ingin mengubah status janji temu ini?')) {
        fetch(`/appointments/{{ $appointment->id }}/status`, {
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