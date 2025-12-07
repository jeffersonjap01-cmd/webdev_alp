@extends('layouts.main')

@section('title', 'Doctor Details - VetCare')

@section('page-title', 'Detail Dokter')
@section('page-description', 'Informasi lengkap dokter')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <img class="h-16 w-16 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($doctor->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $doctor->name }}">
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            {{ $doctor->name }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-stethoscope mr-1.5"></i>
                                {{ $doctor->specialization ?? 'General Practice' }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-envelope mr-1.5"></i>
                                {{ $doctor->email }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $doctor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $doctor->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('doctors') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('doctors.edit', $doctor) }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
            <!-- Doctor Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Dokter</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->phone ?? 'Tidak tersedia' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Spesialisasi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->specialization ?? 'General Practice' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $doctor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $doctor->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Bergabung</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->created_at->format('d M Y') }}</dd>
                        </div>
                        @if($doctor->license_number)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Nomor Lisensi</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->license_number }}</dd>
                        </div>
                        @endif
                        @if($doctor->experience_years)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pengalaman</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->experience_years }} tahun</dd>
                        </div>
                        @endif
                        @if($doctor->education)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Pendidikan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $doctor->education }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Janji Temu Terbaru</h3>
                        <a href="{{ route('appointments') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($doctor->appointments && $doctor->appointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($doctor->appointments->sortByDesc('appointment_time')->take(5) as $appointment)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->pet->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->service_type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M') }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
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
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada janji temu</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statistik</h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Total Janji Temu</dt>
                            <dd class="text-sm text-gray-900">{{ $doctor->appointments ? $doctor->appointments->count() : 0 }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Janji Temu Bulan Ini</dt>
                            <dd class="text-sm text-gray-900">
                                @if($doctor->appointments)
                                    {{ $doctor->appointments->whereMonth('appointment_time', now()->month)->whereYear('appointment_time', now()->year)->count() }}
                                @else
                                    0
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Pasien Unik</dt>
                            <dd class="text-sm text-gray-900">
                                @if($doctor->appointments)
                                    {{ $doctor->appointments->pluck('pet_id')->unique()->count() }}
                                @else
                                    0
                                @endif
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Rekam Medis</dt>
                            <dd class="text-sm text-gray-900">{{ $doctor->medicalRecords ? $doctor->medicalRecords->count() : 0 }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Contact Actions -->
            @if($doctor->phone || $doctor->email)
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Kontak</h3>
                    <div class="space-y-3">
                        @if($doctor->phone)
                        <a href="tel:{{ $doctor->phone }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-phone mr-2"></i>
                            Telepon
                        </a>
                        @endif
                        <a href="mailto:{{ $doctor->email }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-envelope mr-2"></i>
                            Email
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            @if(auth()->user()->role === 'admin')
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        @if($doctor->status === 'inactive')
                        <button onclick="toggleStatus('active')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-check mr-2"></i>
                            Aktifkan
                        </button>
                        @else
                        <button onclick="toggleStatus('inactive')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>
                            Nonaktifkan
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
function toggleStatus(status) {
    if (confirm('Apakah Anda yakin ingin mengubah status dokter ini?')) {
        fetch(`/doctors/{{ $doctor->id }}/toggle-status`, {
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