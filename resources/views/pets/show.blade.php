@extends('layouts.main')

@section('title', 'Pet Details - VetCare')

@section('page-title', 'Detail Hewan Peliharaan')
@section('page-description', 'Informasi lengkap hewan peliharaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-paw text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                            {{ $pet->name }}
                        </h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-tag mr-1.5"></i>
                                {{ $pet->breed ?? 'Unknown Breed' }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-user mr-1.5"></i>
                                    {{ $pet->customer->name ?? 'Unknown Customer' }}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1.5"></i>
                                {{ $pet->age ?? 'Unknown' }} tahun
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('pets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                @if(in_array(auth()->user()->role, ['admin', 'customer']))
                <button id="editPetBtn" onclick="toggleEditMode()" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </button>
                <button id="savePetBtn" onclick="savePet()" style="display: none;" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-save mr-2"></i>
                    Save
                </button>
                <button id="cancelEditBtn" onclick="cancelEdit()" style="display: none;" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Pet Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Hewan</h3>
                    <div class="pet-info">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->species }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Breed</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->breed ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Umur</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->age ?? 'Unknown' }} tahun</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Berat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->weight ?? 'Unknown' }} kg</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($pet->gender ?? 'Unknown') }}</dd>
                        </div>
                        @if($pet->color)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Warna</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->color }}</dd>
                        </div>
                        @endif
                        @if($pet->microchip_id)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID Microchip</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->microchip_id }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Lahir</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->birth_date ? $pet->birth_date->format('d M Y') : 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pet->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($pet->status ?? 'unknown') }}
                                </span>
                            </dd>
                        </div>
                        @if($pet->notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->notes }}</dd>
                        </div>
                        @endif
                    </dl>
                    </div>
                </div>
            </div>

            <!-- Medical Records -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Rekam Medis</h3>
                        <a href="{{ route('pets.medical-records', ['pet' => $pet->id]) }}" class="text-sm text-blue-600 hover:text-blue-500">
                            Lihat Semua
                        </a>
                    </div>
                    
                    @if($pet->medicalRecords && $pet->medicalRecords->count() > 0)
                    <div class="space-y-4">
                        @foreach($pet->medicalRecords->take(3) as $record)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $record->diagnosis ?? 'No Diagnosis' }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $record->treatment ?? 'No Treatment' }}</p>
                                    @if($record->appointment)
                                    <p class="text-xs text-gray-400 mt-1">
                                        Janji Temu: {{ \Carbon\Carbon::parse($record->appointment->appointment_time)->format('d M Y') }}
                                    </p>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400">{{ $record->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($pet->medicalRecords->count() > 3)
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-500">Dan {{ $pet->medicalRecords->count() - 3 }} rekam medis lainnya</p>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-medical text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada rekam medis</p>
                    </div>
                    @endif
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
                    
                    @if($pet->appointments && $pet->appointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($pet->appointments->sortByDesc('appointment_time')->take(5) as $appointment)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $appointment->service_type }}</p>
                                    <p class="text-sm text-gray-500">Dr. {{ $appointment->doctor->name ?? 'Unknown' }}</p>
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
            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Customer</h3>
                    <dl class="space-y-4">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($pet->customer->name ?? 'Unknown') }}&color=7F9CF5&background=EBF4FF" alt="{{ $pet->customer->name ?? 'Unknown' }}">
                            <div class="ml-3">
                                <dt class="text-sm font-medium text-gray-900">{{ $pet->customer->name ?? 'Unknown' }}</dt>
                                <dd class="text-sm text-gray-500">{{ $pet->customer->email ?? 'Unknown' }}</dd>
                            </div>
                        </div>
                        @if($pet->customer && $pet->customer->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->customer->phone }}</dd>
                        </div>
                        @endif
                        @if($pet->customer && $pet->customer->address)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->customer->address }}</dd>
                        </div>
                        @endif
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('customers.show', $pet->customer) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i>
                            Lihat Profil Pemilik
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Statistik</h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Total Janji Temu</dt>
                            <dd class="text-sm text-gray-900">{{ $pet->appointments ? $pet->appointments->count() : 0 }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Rekam Medis</dt>
                            <dd class="text-sm text-gray-900">{{ $pet->medicalRecords ? $pet->medicalRecords->count() : 0 }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Vaksinasi</dt>
                            <dd class="text-sm text-gray-900">
                                @if($pet->vaccinations)
                                    {{ $pet->vaccinations->count() }}
                                @else
                                    0
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="space-y-3">
                        @if(in_array(auth()->user()->role, ['admin', 'customer']))
                        <a href="{{ route('appointments.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Buat Janji Temu
                        </a>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'vet']))
                        <a href="{{ route('medical-records.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-file-medical mr-2"></i>
                            Tambah Rekam Medis
                        </a>
                        @endif
                        
                        <a href="{{ route('pets.medical-records', ['pet' => $pet->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-history mr-2"></i>
                            Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleEditMode() {
    // Hide edit button, show save and cancel buttons
    document.getElementById('editPetBtn').style.display = 'none';
    document.getElementById('savePetBtn').style.display = 'inline-flex';
    document.getElementById('cancelEditBtn').style.display = 'inline-flex';
    
    // Convert pet information to editable form
    const petInfo = document.querySelector('.pet-info');
    if (petInfo) {
        petInfo.innerHTML = `
            <form id="editPetForm" action="{{ route('pets.update', $pet) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Hewan</label>
                        <input type="text" name="name" value="{{ $pet->name }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Hewan</label>
                        <select name="species" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="Dog" {{ $pet->species == 'Dog' ? 'selected' : '' }}>Anjing</option>
                            <option value="Cat" {{ $pet->species == 'Cat' ? 'selected' : '' }}>Kucing</option>
                            <option value="Bird" {{ $pet->species == 'Bird' ? 'selected' : '' }}>Burung</option>
                            <option value="Rabbit" {{ $pet->species == 'Rabbit' ? 'selected' : '' }}>Kelinci</option>
                            <option value="Hamster" {{ $pet->species == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                            <option value="Fish" {{ $pet->species == 'Fish' ? 'selected' : '' }}>Ikan</option>
                            <option value="Other" {{ $pet->species == 'Other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Breed/Ras</label>
                        <input type="text" name="breed" value="{{ $pet->breed ?? '' }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Umur (tahun)</label>
                        <input type="number" name="age" value="{{ $pet->age ?? '' }}" min="0" max="50"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Berat (kg)</label>
                        <input type="number" name="weight" value="{{ $pet->weight ?? '' }}" min="0" step="0.1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="male" {{ $pet->gender == 'male' ? 'selected' : '' }}>Jantan</option>
                            <option value="female" {{ $pet->gender == 'female' ? 'selected' : '' }}>Betina</option>
                            <option value="unknown" {{ $pet->gender == 'unknown' ? 'selected' : '' }}>Unknown</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Warna</label>
                        <input type="text" name="color" value="{{ $pet->color ?? '' }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $pet->birth_date ? $pet->birth_date->format('Y-m-d') : '' }}"
                               max="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="active" {{ $pet->status == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ $pet->status == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ $pet->notes ?? '' }}</textarea>
                </div>
            </form>
        `;
    }
}

function savePet() {
    const form = document.getElementById('editPetForm');
    if (form) {
        form.submit();
    }
}

function cancelEdit() {
    // Reload the page to cancel editing
    window.location.reload();
}
</script>
@endpush