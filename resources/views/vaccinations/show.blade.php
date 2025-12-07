@extends('layouts.main')

@section('title', 'Vaccination Details - VetCare')

@section('page-title', 'Detail Vaksinasi')
@section('page-description', 'Informasi lengkap vaksinasi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-syringe text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            Vaccinasi - {{ $vaccination->pet->name ?? 'Unknown Pet' }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-pills mr-1.5"></i>
                                {{ $vaccination->vaccine_type }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1.5"></i>
                                {{ $vaccination->scheduled_date ? $vaccination->scheduled_date->format('d M Y') : 'No Date' }}
                            </div>
                            @if($vaccination->completed_date)
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-check mr-1.5"></i>
                                Selesai: {{ $vaccination->completed_date->format('d M Y') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('vaccinations') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'vet']))
                <a href="{{ route('vaccinations.edit', $vaccination) }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
            <!-- Vaccination Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detail Vaksinasi</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Vaccine</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->vaccine_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
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
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Jadwal</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->scheduled_date ? $vaccination->scheduled_date->format('d M Y') : 'Tidak dijadwalkan' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->completed_date ? $vaccination->completed_date->format('d M Y') : 'Belum selesai' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Batch Number</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->batch_number ?? 'Tidak tersedia' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dosis</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->dosage ?? 'Tidak tersedia' }}</dd>
                        </div>
                        @if($vaccination->administered_by)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Diberikan Oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->administered_by }}</dd>
                        </div>
                        @endif
                        @if($vaccination->next_due_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jadwal Berikutnya</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->next_due_date->format('d M Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                        @if($vaccination->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Medical Record -->
            @if($vaccination->medicalRecord)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Rekam Medis</h3>
                        <a href="{{ route('medical-records.show', $vaccination->medicalRecord) }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Detail
                        </a>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900">{{ $vaccination->medicalRecord->diagnosis ?? 'No Diagnosis' }}</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $vaccination->medicalRecord->treatment ?? 'No Treatment' }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $vaccination->medicalRecord->created_at->format('d M Y') }}</p>
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
                                <dt class="text-sm font-medium text-gray-900">{{ $vaccination->pet->name ?? 'Unknown' }}</dt>
                                <dd class="text-sm text-gray-500">{{ $vaccination->pet->type ?? 'Unknown Type' }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Breed</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->pet->breed ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Umur</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->pet->age ?? 'Unknown' }} tahun</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Berat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->pet->weight ?? 'Unknown' }} kg</dd>
                        </div>
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('pets.show', $vaccination->pet) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-paw mr-2"></i>
                            Lihat Profil Hewan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Pemilik</h3>
                    <dl class="space-y-4">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($vaccination->pet->owner->name ?? 'Unknown') }}&color=7F9CF5&background=EBF4FF" alt="{{ $vaccination->pet->owner->name ?? 'Unknown' }}">
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">{{ $vaccination->pet->owner->name ?? 'Unknown' }}</dt>
                                <dd class="text-sm text-gray-500">{{ $vaccination->pet->owner->email ?? 'Unknown' }}</dd>
                            </div>
                        </div>
                        @if($vaccination->pet->owner && $vaccination->pet->owner->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $vaccination->pet->owner->phone }}</dd>
                        </div>
                        @endif
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('owners.show', $vaccination->pet->owner) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i>
                            Lihat Profil Pemilik
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(in_array(auth()->user()->role, ['admin', 'vet']))
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        @if($vaccination->status === 'scheduled')
                        <button onclick="markAsCompleted()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>
                            Tandai Selesai
                        </button>
                        @endif
                        @if($vaccination->status === 'completed' && $vaccination->next_due_date)
                        <a href="{{ route('vaccinations.create') }}?pet_id={{ $vaccination->pet_id }}&vaccine_type={{ $vaccination->vaccine_type }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-plus mr-2"></i>
                            Jadwalkan Selanjutnya
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function markAsCompleted() {
    if (confirm('Apakah Anda yakin ingin menandai vaksinasi ini sebagai selesai?')) {
        fetch(`/vaccinations/{{ $vaccination->id }}/complete`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                completed_date: new Date().toISOString().split('T')[0],
                status: 'completed'
            })
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