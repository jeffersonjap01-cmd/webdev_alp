@extends('layouts.main')

@section('title', 'Appointment Details - VetCare')

@section('page-title', 'Appointment Details')
@section('page-description', 'Complete appointment information')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Appointment - {{ $appointment->pet->name ?? 'Unknown Pet' }}
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
            <div class="mt-4 flex flex-wrap gap-2 md:mt-0 md:ml-4">
                <a href="{{ route('appointments') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                
                {{-- Doctor Workflow Actions --}}
                @if(auth()->user()->role === 'doctor')
                    @if($appointment->status === 'pending')
                    <form action="{{ route('appointments.accept', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Accept
                        </button>
                    </form>
                    <form action="{{ route('appointments.decline', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to decline this appointment?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-times mr-2"></i>
                            Decline
                        </button>
                    </form>
                    @endif

                    @if($appointment->status === 'accepted')
                    <form action="{{ route('appointments.start', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-play mr-2"></i>
                            Start Appointment
                        </button>
                    </form>
                    @endif

                    @if(in_array($appointment->status, ['accepted', 'in_progress']))
                    <form action="{{ route('appointments.complete', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-check-circle mr-2"></i>
                            Complete & Record
                        </button>
                    </form>
                    @endif
                @endif
                
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                @endif
                
                @if(auth()->user()->role === 'customer' && $appointment->status === 'pending')
                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you sure you want to cancel this appointment?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-times mr-2"></i>
                        Cancel Appointment
                    </button>
                </form>
                @endif
                
                @if(auth()->user()->role === 'admin')
                <button type="button" onclick="deleteAppointment()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-trash mr-2"></i>
                    Delete
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
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Appointment Details</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @switch($appointment->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('accepted') bg-green-100 text-green-800 @break
                                        @case('declined') bg-red-100 text-red-800 @break
                                        @case('in_progress') bg-blue-100 text-blue-800 @break
                                        @case('completed') bg-indigo-100 text-indigo-800 @break
                                        @case('cancelled') bg-gray-100 text-gray-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    @switch($appointment->status)
                                        @case('pending') Pending @break
                                        @case('accepted') Accepted @break
                                        @case('declined') Declined @break
                                        @case('in_progress') In Progress @break
                                        @case('completed') Completed @break
                                        @case('cancelled') Cancelled @break
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

            {{-- In-progress: doctor can fill diagnosis / medical record and add prescription --}}
            @if(auth()->user()->role === 'doctor' && $appointment->status === 'in_progress' && auth()->user()->doctor && auth()->user()->doctor->id === ($appointment->doctor->id ?? null))
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">In-Progress: Record Diagnosis & Prescribe</h3>

                    <!-- Medical Record Form -->
                    <form action="{{ route('medical-records.store') }}" method="POST" class="mb-6">
                        @csrf
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                        <input type="hidden" name="doctor_id" value="{{ auth()->user()->doctor->id }}">
                        <input type="hidden" name="pet_id" value="{{ $appointment->pet->id }}">

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Symptoms</label>
                                <textarea name="symptoms" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notes / Treatment</label>
                                <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Recommendation</label>
                                <input type="text" name="recommendation" class="mt-1 block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diagnoses</label>
                                <div class="grid grid-cols-2 gap-2 mb-2 text-xs font-semibold text-gray-600">
                                    <div class="pl-2">Diagnosis Name</div>
                                    <div class="pl-2">Description</div>
                                </div>
                                <div id="diagnoses-container" class="space-y-2">
                                    <div class="flex gap-2">
                                        <input type="text" name="diagnoses[0][name]" placeholder="Diagnosis name" class="block w-1/2 border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                                        <input type="text" name="diagnoses[0][description]" placeholder="Description" class="block w-1/2 border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                                    </div>
                                </div>
                                <button type="button" onclick="addDiagnosis()" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">Add diagnosis</button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Medications</label>
                                <div class="grid grid-cols-4 gap-2 mb-2 text-xs font-semibold text-gray-600">
                                    <div class="pl-2">Name</div>
                                    <div class="pl-2">Dosage</div>
                                    <div class="pl-2">Frequency</div>
                                    <div class="pl-2">Duration</div>
                                </div>
                                <div id="medications-container" class="space-y-2">
                                    <div class="grid grid-cols-4 gap-2">
                                        <input type="text" name="medications[0][name]" placeholder="Name" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                                        <input type="text" name="medications[0][dosage]" placeholder="Dosage" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                                        <input type="text" name="medications[0][frequency]" placeholder="Frequency" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                                        <input type="text" name="medications[0][duration]" placeholder="Duration" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                                    </div>
                                </div>
                                <button type="button" onclick="addMedication()" class="mt-2 inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-100">Add medication</button>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Save Medical Record</button>
                            </div>
                        </div>
                    </form>
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
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->pet->species ?? 'Unknown' }}</dd>
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

            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Customer</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->user->name ?? 'Unknown' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $appointment->user->email ?? 'Unknown' }}</dd>
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
    if (confirm('Are you sure you want to change the status of this appointment?')) {
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
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the status');
        });
    }
}

function deleteAppointment() {
    if (confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('appointments.destroy', $appointment) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Dynamic fields for in-progress forms
let diagIndex = 1;
function addDiagnosis() {
    const container = document.getElementById('diagnoses-container');
    const row = document.createElement('div');
    row.className = 'flex gap-2';
    row.innerHTML = `<input type="text" name="diagnoses[${diagIndex}][name]" placeholder="Diagnosis name" class="block w-1/2 border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                     <input type="text" name="diagnoses[${diagIndex}][description]" placeholder="Description" class="block w-1/2 border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                     <button type="button" onclick="this.parentNode.remove()" class="ml-2 text-red-500 hover:text-red-700">Remove</button>`;
    container.appendChild(row);
    diagIndex++;
}

let medIndex = 1;
function addMedication() {
    const container = document.getElementById('medications-container');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-4 gap-2';
    row.innerHTML = `<input type="text" name="medications[${medIndex}][name]" placeholder="Name" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                     <input type="text" name="medications[${medIndex}][dosage]" placeholder="Dosage" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                     <input type="text" name="medications[${medIndex}][frequency]" placeholder="Frequency" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                     <input type="text" name="medications[${medIndex}][duration]" placeholder="Duration" class="block w-full border-gray-300 rounded-md shadow-md hover:border-blue-500 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all" />
                     <button type="button" onclick="this.parentNode.remove()" class="ml-2 text-red-500 hover:text-red-700">Remove</button>`;
    container.appendChild(row);
    medIndex++;
}
</script>
@endsection