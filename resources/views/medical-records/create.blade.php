@extends('layouts.main')

@section('title', 'Create Medical Record - VetCare')

@section('page-title', 'Tambah Rekam Medis')
@section('page-description', 'Tambahkan rekam medis baru')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Tambah Rekam Medis Baru
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Isi form di bawah untuk menambahkan rekam medis baru
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('medical-records') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('medical-records.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Hidden doctor_id field -->
                @if($selectedAppointment && $selectedAppointment->doctor_id)
                    <input type="hidden" name="doctor_id" value="{{ $selectedAppointment->doctor_id }}">
                @endif
                
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                    
                    <!-- Pet Selection -->
                    <div>
                        <label for="pet_id" class="block text-sm font-medium text-gray-700">Hewan Peliharaan *</label>
                        <select name="pet_id" id="pet_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('pet_id') border-red-300 @enderror">
                            <option value="">Pilih Hewan Peliharaan</option>
                                   @foreach($pets ?? \App\Models\Pet::with('customer')->get() as $pet)
                                       <option value="{{ $pet->id }}" 
                                           {{ old('pet_id', $selectedAppointment->pet_id ?? '') == $pet->id ? 'selected' : '' }}>
                                           {{ $pet->name }} - {{ $pet->customer->name ?? 'No Customer' }} ({{ $pet->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('pet_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Appointment Selection -->
                    <div>
                        <label for="appointment_id" class="block text-sm font-medium text-gray-700">Janji Temu *</label>
                        <select name="appointment_id" id="appointment_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('appointment_id') border-red-300 @enderror">
                            <option value="">Pilih Janji Temu</option>
                            @if(isset($appointments))
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->id }}" 
                                        {{ old('appointment_id', $selectedAppointment->id ?? '') == $appointment->id ? 'selected' : '' }}>
                                        {{ $appointment->pet->name }} - {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, H:i') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('appointment_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Medis</h3>
                    
                    <!-- Diagnosis -->
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis *</label>
                        <input type="text" name="diagnosis" id="diagnosis" required 
                               value="{{ old('diagnosis') }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('diagnosis') border-red-300 @enderror" 
                               placeholder="Contoh: Infeksi kulit, Demam, dll">
                        @error('diagnosis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Symptoms -->
                    <div>
                        <label for="symptoms" class="block text-sm font-medium text-gray-700">Gejala</label>
                        <textarea name="symptoms" id="symptoms" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('symptoms') border-red-300 @enderror" 
                                  placeholder="Deskripsikan gejala yang diamati">{{ old('symptoms', isset($examinationData) && !empty($examinationData['diagnoses']) ? implode("; ", array_map(fn($d) => ($d['name'] ?? '') . ': ' . ($d['description'] ?? ''), $examinationData['diagnoses'])) : '') }}</textarea>
                        @error('symptoms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Treatment -->
                    <div>
                        <label for="treatment" class="block text-sm font-medium text-gray-700">Perawatan/Tindakan *</label>
                        <textarea name="treatment" id="treatment" rows="4" required 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('treatment') border-red-300 @enderror" 
                                  placeholder="Deskripsikan perawatan atau tindakan yang dilakukan">{{ old('treatment') }}</textarea>
                        @error('treatment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Consultation Fee -->
                    <div>
                        <label for="consultation_fee" class="block text-sm font-medium text-gray-700">Biaya Konsultasi (Rp) *</label>
                        <input type="number" name="consultation_fee" id="consultation_fee" min="0" step="1000" required
                               value="{{ old('consultation_fee', $selectedAppointment && $selectedAppointment->doctor && $selectedAppointment->doctor->consultation_fee ? $selectedAppointment->doctor->consultation_fee : (env('CONSULTATION_FEE', 150000))) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('consultation_fee') border-red-300 @enderror" 
                               placeholder="Contoh: 150000">
                        @error('consultation_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    @if(isset($examinationData) && !empty($examinationData['diagnoses']))
                    <!-- Pre-filled Diagnosis from Examination -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-900 mb-2">Data dari Pemeriksaan:</p>
                        <ul class="list-disc list-inside text-sm text-blue-800">
                            @foreach($examinationData['diagnoses'] as $diag)
                                <li>{{ $diag['name'] }}: {{ $diag['description'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-300 @enderror" 
                                  placeholder="Catatan tambahan">{{ old('notes', isset($examinationData) ? "Temperature: " . ($examinationData['temperature'] ?? '') . ", Heart rate: " . ($examinationData['heart_rate'] ?? '') . ". " . ($examinationData['notes'] ?? '') : '') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('medical-records') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-populate appointments based on selected pet
document.getElementById('pet_id').addEventListener('change', function() {
    const petId = this.value;
    const appointmentSelect = document.getElementById('appointment_id');
    
    // Clear current options
    appointmentSelect.innerHTML = '<option value="">Pilih Janji Temu</option>';
    
    if (petId) {
        // You can implement AJAX call here to fetch appointments for the selected pet
        // For now, we'll just show a message
        console.log('Fetching appointments for pet:', petId);
    }
});
</script>
@endsection