@extends('layouts.main')

@section('title', 'Create Appointment - VetCare')

@section('page-title', 'Create Appointment')
@section('page-description', 'Create a new pet appointment')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Create New Appointment
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Fill out the form below to create a new appointment
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('appointments') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="relative">
        <div class="rounded-xl p-1" style="background: linear-gradient(180deg, rgba(59,130,246,0.06), rgba(99,102,241,0.03));">
            <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                <div class="px-6 py-6 sm:p-8">
                    <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Pet Selection -->
                <div class="bg-gray-50 p-3 rounded-lg ring-1 ring-blue-50">
                    <label for="pet_id" class="block text-sm font-medium text-blue-600">Hewan Peliharaan *</label>
                    <select name="pet_id" id="pet_id" required class="mt-1 block w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 sm:text-sm @error('pet_id') border-red-300 @enderror">
                        <option value="">Pilih Hewan Peliharaan</option>
                        @foreach($pets ?? \App\Models\Pet::with('customer')->get() as $pet)
                            <option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }} - {{ $pet->customer->name ?? 'No Customer' }}
                            </option>
                        @endforeach
                    </select>
                    @error('pet_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor Selection -->
                <div class="bg-gray-50 p-3 rounded-lg ring-1 ring-blue-50">
                    <label for="doctor_id" class="block text-sm font-medium text-blue-600">Dokter *</label>
                    <select name="doctor_id" id="doctor_id" required class="mt-1 block w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 sm:text-sm @error('doctor_id') border-red-300 @enderror">
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors ?? \App\Models\Doctor::active()->get() as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }} - {{ $doctor->specialization ?? 'General Practice' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="bg-gray-50 p-3 rounded-lg ring-1 ring-blue-50">
                        <label for="appointment_date" class="block text-sm font-medium text-blue-600">Tanggal *</label>
                           <input type="date" name="appointment_date" id="appointment_date" required 
                               value="{{ old('appointment_date') }}" 
                               min="{{ date('Y-m-d') }}"
                               class="mt-1 block w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 sm:text-sm @error('appointment_date') border-red-300 @enderror">
                        @error('appointment_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="bg-gray-50 p-3 rounded-lg ring-1 ring-blue-50">
                        <label for="appointment_time" class="block text-sm font-medium text-blue-600">Waktu *</label>
                           <input type="time" name="appointment_time" id="appointment_time" required 
                               value="{{ old('appointment_time') }}"
                               class="mt-1 block w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 sm:text-sm @error('appointment_time') border-red-300 @enderror">
                        @error('appointment_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Service Type -->
                <div class="bg-gray-50 p-3 rounded-lg ring-1 ring-blue-50">
                    <label for="service_type" class="block text-sm font-medium text-blue-600">Jenis Layanan *</label>
                    <select name="service_type" id="service_type" required class="mt-1 block w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 sm:text-sm @error('service_type') border-red-300 @enderror">
                        <option value="">Pilih Jenis Layanan</option>
                        <option value="General Checkup" {{ old('service_type') == 'General Checkup' ? 'selected' : '' }}>Pemeriksaan Umum</option>
                        <option value="Vaccination" {{ old('service_type') == 'Vaccination' ? 'selected' : '' }}>Vaksinasi</option>
                        <option value="Surgery" {{ old('service_type') == 'Surgery' ? 'selected' : '' }}>Operasi</option>
                        <option value="Grooming" {{ old('service_type') == 'Grooming' ? 'selected' : '' }}>Grooming</option>
                    </select>
                    @error('service_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div class="bg-gray-50 p-3 rounded-lg ring-1 ring-blue-50">
                    <label for="duration" class="block text-sm font-medium text-blue-600">Durasi (menit) *</label>
                    <select name="duration" id="duration" required class="mt-1 block w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 sm:text-sm @error('duration') border-red-300 @enderror">
                        <option value="">Pilih Durasi</option>
                        <option value="15" {{ old('duration') == '15' ? 'selected' : '' }}>15 menit</option>
                        <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 menit</option>
                        <option value="45" {{ old('duration') == '45' ? 'selected' : '' }}>45 menit</option>
                        <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>60 menit</option>
                        <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>90 menit</option>
                    </select>
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="bg-gray-50 p-3 rounded-lg ring-1 ring-blue-50">
                    <label for="notes" class="block text-sm font-medium text-blue-600">Catatan</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="mt-1 block w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-black bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 sm:text-sm @error('notes') border-red-300 @enderror" 
                              placeholder="Additional notes for this appointment">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Opsional. Berikan informasi tambahan yang mungkin membantu dokter.</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('appointments') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-lg text-sm font-medium text-black bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-300">
                        <i class="fas fa-save mr-2"></i>
                        Create Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
    </div>
</div>

<script>
// Auto-populate available doctors based on selected date
document.getElementById('appointment_date').addEventListener('change', function() {
    const selectedDate = this.value;
    const doctorSelect = document.getElementById('doctor_id');
    
    // You can implement AJAX call here to fetch available doctors for the selected date
    // For now, we'll just show a message
    if (selectedDate) {
        console.log('Fetching available doctors for date:', selectedDate);
    }
});

// Validate appointment time (prevent past dates/times)
document.getElementById('appointment_date').addEventListener('change', function() {
    const selectedDate = this.value;
    const today = new Date().toISOString().split('T')[0];
    
    if (selectedDate < today) {
        alert('Tidak dapat memilih tanggal yang sudah lewat');
        this.value = '';
    }
});
</script>
@endsection