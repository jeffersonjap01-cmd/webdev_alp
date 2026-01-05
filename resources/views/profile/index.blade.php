@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
            <div class="hidden sm:inline-block">
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'doctor' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Basic Information -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <h6 class="text-lg font-semibold text-gray-900">Informasi Dasar</h6>
                        <button onclick="openEditModal()" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-white border border-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-200">
                            <i class="fas fa-edit mr-1.5"></i>
                            Edit
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        @if($user->role === 'doctor' && isset($profileData['doctor_info']['photo_url']) && $profileData['doctor_info']['photo_url'])
                            <img class="w-36 h-36 rounded-full mx-auto mb-4 object-cover border-4 border-blue-100 shadow-md" 
                                 src="{{ $profileData['doctor_info']['photo_url'] }}" alt="Foto Profil">
                        @else
                            <div class="w-36 h-36 rounded-full mx-auto mb-4 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center border-4 border-blue-100 shadow-md">
                                <span class="text-white text-4xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h5 class="text-xl font-bold text-gray-900 mb-2">{{ $profileData['basic_info']['name'] }}</h5>
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-center text-gray-600">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>
                                <span class="text-sm">{{ $profileData['basic_info']['email'] }}</span>
                            </div>
                            <div class="flex items-center justify-center text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                <span>Anggota sejak: {{ $profileData['basic_info']['member_since'] }}</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'doctor' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                <i class="fas fa-user-shield mr-1.5"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Specific Info -->
            @if($user->role === 'customer' && isset($profileData['owner_info']))
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                        <h6 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-address-card mr-2 text-green-600"></i>
                            Informasi Kontak
                        </h6>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Telepon</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $profileData['owner_info']['phone'] ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-map-marker-alt text-green-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Alamat</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $profileData['owner_info']['address'] ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Doctor Specific Info -->
            @if($user->role === 'doctor' && isset($profileData['doctor_info']))
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h6 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-user-md mr-2 text-blue-600"></i>
                            Informasi Profesional
                        </h6>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Telepon</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $profileData['doctor_info']['phone'] ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-stethoscope text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Spesialisasi</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $profileData['doctor_info']['specialization'] ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 mt-0.5">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-1">Status</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $profileData['doctor_info']['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($profileData['doctor_info']['status']) }}
                                </span>
                                <form action="{{ route('profile.toggle-status') }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-medium underline focus:outline-none {{ $profileData['doctor_info']['status'] === 'active' ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $profileData['doctor_info']['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($profileData['doctor_info']['bio'])
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs font-medium text-gray-500 uppercase mb-2">Bio</p>
                            <p class="text-sm text-gray-900 leading-relaxed">{{ $profileData['doctor_info']['bio'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Role-specific Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Dashboard -->
            @if($user->role === 'customer' && isset($profileData['owner_info']))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white shadow rounded-lg border-l-4 border-blue-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
                                    Total Hewan Peliharaan
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['owner_info']['total_pets'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-paw text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg border-l-4 border-green-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">
                                    Total Janji Temu
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['owner_info']['total_appointments'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-calendar text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg border-l-4 border-blue-400 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wide mb-1">
                                    Total Tagihan
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['owner_info']['total_invoices'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-receipt text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg border-l-4 border-yellow-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-yellow-600 uppercase tracking-wide mb-1">
                                    Terdaftar Sejak
                                </div>
                                <div class="text-lg font-bold text-gray-900">
                                    {{ $profileData['owner_info']['registered_date'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-calendar-check text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Doctor Dashboard -->
            @if($user->role === 'doctor' && isset($profileData['doctor_info']))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white shadow rounded-lg border-l-4 border-blue-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
                                    Total Janji Temu
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['doctor_info']['total_appointments'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-calendar text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg border-l-4 border-green-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">
                                    Rekam Medis
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['doctor_info']['total_medical_records'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-file-medical text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg border-l-4 border-blue-400 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wide mb-1">
                                    Resep Obat
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['doctor_info']['total_prescriptions'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-pills text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Admin Dashboard -->
            @if($user->role === 'admin' && isset($profileData['admin_info']))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div class="bg-white shadow rounded-lg border-l-4 border-green-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">
                                    Total Doctors
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['admin_info']['total_doctors'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-user-md text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg border-l-4 border-blue-400 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wide mb-1">
                                    Total Customers
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['admin_info']['total_customers'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-user-friends text-3xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg border-l-4 border-yellow-500 p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold text-yellow-600 uppercase tracking-wide mb-1">
                                    Total Appointments
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $profileData['admin_info']['total_appointments'] }}
                                </div>
                            </div>
                            <div class="text-gray-300">
                                <i class="fas fa-calendar text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h6 class="text-lg font-semibold text-gray-900">Aksi Cepat</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($user->role === 'customer')
                            <a href="{{ route('pets.create') }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i> Tambah Hewan Peliharaan
                            </a>
                            <a href="{{ route('appointments.create') }}" class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-calendar-plus mr-2"></i> Buat Janji Temu
                            </a>
                            <a href="{{ route('pets.index') }}" class="flex items-center justify-center px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                                <i class="fas fa-paw mr-2"></i> Lihat Hewan Peliharaan
                            </a>
                        @elseif($user->role === 'doctor')
                            <a href="{{ route('medical-records.create') }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-file-medical mr-2"></i> Buat Rekam Medis
                            </a>
                            <a href="{{ route('prescriptions.create') }}" class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-pills mr-2"></i> Buat Resep Obat
                            </a>
                        @elseif($user->role === 'admin')
                            <a href="{{ route('doctors.create') }}" class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                                <i class="fas fa-user-md mr-2"></i> Tambah Doctor
                            </a>
                            
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Informasi Dasar</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" id="editProfileForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email / Username</label>
                        <input type="email" name="email" id="email" value="{{ $user->email }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Saat Ini <span class="text-xs text-gray-500">(wajib jika ingin mengubah password)</span>
                        </label>
                        <input type="password" name="current_password" id="current_password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Baru <span class="text-xs text-gray-500">(opsional)</span>
                        </label>
                        <input type="password" name="password" id="password" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-1.5"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Password validation
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const currentPassword = document.getElementById('current_password').value;
    
    // If password is filled, require current password and confirmation
    if (password) {
        if (!currentPassword) {
            e.preventDefault();
            alert('Password saat ini wajib diisi jika ingin mengubah password!');
            return false;
        }
        if (password !== passwordConfirmation) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak cocok!');
            return false;
        }
        if (password.length < 6) {
            e.preventDefault();
            alert('Password baru minimal 6 karakter!');
            return false;
        }
    }
});

@if(session('success'))
    openEditModal();
    setTimeout(function() {
        closeEditModal();
        alert('{{ session('success') }}');
        location.reload();
    }, 100);
@endif

@if($errors->any())
    openEditModal();
    @foreach($errors->all() as $error)
        alert('{{ $error }}');
    @endforeach
@endif
</script>
@endsection
