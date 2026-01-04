@extends('layouts.main')

@section('title', 'Examination - ' . optional($appointment->pet)->name)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="examinationForm()">

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Examination: {{ optional($appointment->pet)->name ?? 'Unknown' }}
                </h2>
                    <p class="mt-1 text-sm text-gray-500">
                    Patient ID: #{{ optional($appointment->pet)->id ?? '-' }} | Owner: {{ optional(optional($appointment->pet)->user)->name ?? 'Unknown' }}
                </p>
            </div>
        </div>

        <form action="{{ route('doctor.examination.store', $appointment) }}" method="POST" id="examForm">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Left Column: Patient Info & Vitals -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Patient Info Card -->
                    <div class="bg-white shadow rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Patient Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase">Breed</label>
                                    <p class="text-gray-700">{{ optional($appointment->pet)->breed ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase">Age</label>
                                    <p class="text-gray-700">{{ optional($appointment->pet)->age ?? 'Unknown' }} years</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase">Weight</label>
                                    <p class="text-gray-700">{{ optional($appointment->pet)->weight ?? 'N/A' }} kg</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-400 uppercase">Gender</label>
                                    <p class="text-gray-700">{{ optional($appointment->pet)->gender ? ucfirst(optional($appointment->pet)->gender) : 'Unknown' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vitals Input Card -->
                    <div class="bg-white shadow rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Current Vitals</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="temperature" class="block text-sm font-medium text-gray-700">Temperature
                                        (°C)</label>
                                    <input type="number" step="0.1" name="temperature" id="temperature" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="38.5">
                                </div>
                                <div>
                                    <label for="heart_rate" class="block text-sm font-medium text-gray-700">Heart Rate
                                        (bpm)</label>
                                    <input type="number" name="heart_rate" id="heart_rate" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="120">
                                </div>
                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-700">Current Weight
                                        (kg)</label>
                                    <input type="number" step="0.1" name="weight" id="weight" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        value="{{ optional($appointment->pet)->weight }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Examination & Plan -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Clinical Findings -->
                    <div class="bg-white shadow rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Clinical Findings</h3>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Examination Notes</label>
                                <textarea name="notes" id="notes" rows="4" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Describe chief complaint, observations, and physical exam findings..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Billing -->
                    <div class="bg-white shadow rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Billing</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="consultation_fee" class="block text-sm font-medium text-gray-700">Consultation Fee (Rp)</label>
                                    <input type="number" name="consultation_fee" id="consultation_fee" step="1000"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        value="{{ old('consultation_fee', optional($appointment->doctor)->consultation_fee ?? '') }}"
                                        placeholder="e.g. 200000">
                                </div>
                                <div>
                                    <label for="medication_fee" class="block text-sm font-medium text-gray-700">Medication Fee (total, Rp)</label>
                                    <input type="number" name="medication_fee" id="medication_fee" step="1000"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                        value="{{ old('medication_fee', '') }}"
                                        placeholder="e.g. 50000">
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Optional: enter fees manually based on patient condition. If left empty, defaults will be used.</p>
                        </div>
                    </div>

                    <!-- Diagnoses -->
                    <div class="bg-white shadow rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Diagnosis</h3>
                                <button type="button" @click="addDiagnosis()"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                                    <i class="fas fa-plus mr-1"></i> Add
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(diag, index) in diagnoses" :key="index">
                                    <div class="bg-blue-50 p-4 rounded-lg relative">
                                        <button type="button" @click="removeDiagnosis(index)"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                                            x-show="diagnoses.length > 1">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 uppercase">Condition /
                                                    Disease</label>
                                                <input type="text" :name="'diagnoses['+index+'][name]'" x-model="diag.name"
                                                    required
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                    placeholder="e.g. Otitis Externa">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 uppercase">Notes /
                                                    Severity</label>
                                                <input type="text" :name="'diagnoses['+index+'][description]'"
                                                    x-model="diag.description" required
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                    placeholder="e.g. Mild inflammation, left ear">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Prescription -->
                    <div class="bg-white shadow rounded-2xl overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Prescription</h3>
                                <button type="button" @click="addMedication()"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                                    <i class="fas fa-plus mr-1"></i> Add Med
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-if="medications.length === 0">
                                    <p class="text-sm text-center text-gray-500 italic py-4">No medications prescribed yet.
                                    </p>
                                </template>

                                <template x-for="(med, index) in medications" :key="index">
                                    <div class="bg-green-50 p-4 rounded-lg relative border border-green-100">
                                        <button type="button" @click="removeMedication(index)"
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="col-span-2 md:col-span-1">
                                                <label class="block text-xs font-medium text-gray-500 uppercase">Drug
                                                    Name</label>
                                                <input type="text" :name="'medications['+index+'][name]'" x-model="med.name"
                                                    required
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                    placeholder="e.g. Amoxicillin">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-500 uppercase">Dosage</label>
                                                <input type="text" :name="'medications['+index+'][dosage]'"
                                                    x-model="med.dosage" required
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                    placeholder="e.g. 250mg">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-500 uppercase">Frequency</label>
                                                <input type="text" :name="'medications['+index+'][frequency]'"
                                                    x-model="med.frequency" required
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                    placeholder="e.g. Twice daily">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-500 uppercase">Duration</label>
                                                <input type="text" :name="'medications['+index+'][duration]'"
                                                    x-model="med.duration" required
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                    placeholder="e.g. 7 days">
                                            </div>
                                            <div class="col-span-2">
                                                <label
                                                    class="block text-xs font-medium text-gray-500 uppercase">Instructions</label>
                                                <input type="text" :name="'medications['+index+'][instructions]'"
                                                    x-model="med.instructions"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                    placeholder="e.g. Give with food">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-4">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 font-medium">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-check-circle mr-2"></i>
                            Finish Examination
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function examinationForm() {
                return {
                    diagnoses: [{ name: '', description: '' }],
                    medications: [],

                    addDiagnosis() {
                        this.diagnoses.push({ name: '', description: '' });
                    },
                    removeDiagnosis(index) {
                        this.diagnoses.splice(index, 1);
                    },
                    addMedication() {
                        this.medications.push({ name: '', dosage: '', frequency: '', duration: '', instructions: '' });
                    },
                    removeMedication(index) {
                        this.medications.splice(index, 1);
                    }
                }
            }
        </script>
    @endpush
@endsection