<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\Medication;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    

    /**
     * List all medical records
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = MedicalRecord::with(['pet.customer.user', 'doctor.user', 'appointment']);

        // Customer sees only their pets
        if ($user->role === 'customer' && $user->customer) {
            $query->whereHas('pet', fn($q) =>
                $q->where('customer_id', $user->customer->id)
            );
        }

        // Doctor sees only their own patients
        if ($user->role === 'doctor' && $user->doctor) {
            $query->where('doctor_id', $user->doctor->id);
        }

        $records = $query->latest()->paginate(15);

        return view('medical-records.index', compact('records'));
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        return view('medical-records.create', [
            'appointments' => Appointment::with(['pet.customer', 'doctor'])->get(),
            'pets'         => Pet::with('customer')->get(),
            'doctors'      => Doctor::active()->get(),
            'selectedAppointment' => $request->appointment_id
                ? Appointment::with(['pet.customer', 'doctor'])->find($request->appointment_id)
                : null,
        ]);
    }

    /**
     * Store medical record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'pet_id'         => 'required|exists:pets,id',
            'symptoms'       => 'nullable|string',
            'notes'          => 'nullable|string',
            'recommendation' => 'nullable|string',
            'diagnoses'      => 'nullable|array',
            'medications'    => 'nullable|array',
        ]);

        $record = MedicalRecord::create($validated);

        // Insert diagnoses
        if (!empty($validated['diagnoses'])) {
            foreach ($validated['diagnoses'] as $d) {
                Diagnosis::create([
                    'medical_record_id' => $record->id,
                    'diagnosis_name'    => $d['name'],
                    'description'       => $d['description'] ?? null,
                ]);
            }
        }

        // Insert medications
        if (!empty($validated['medications'])) {
            foreach ($validated['medications'] as $m) {
                Medication::create([
                    'medical_record_id' => $record->id,
                    'medicine_name'     => $m['name'],
                    'dosage'            => $m['dosage'] ?? null,
                    'frequency'         => $m['frequency'] ?? null,
                    'duration'          => $m['duration'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('medical-records.show', $record)
            ->with('success', 'Rekam medis berhasil dibuat!');
    }

    /**
     * Show a medical record
     */
    public function show(MedicalRecord $record)
    {
        $user = Auth::user();

        // Customer can only see their own pet
        if ($user->role === 'customer') {
            if (!$user->customer || $record->pet->customer_id !== $user->customer->id) {
                abort(403, 'Anda tidak memiliki akses untuk rekam medis ini.');
            }
        }

        $record->load([
            'diagnoses',
            'medications',
            'doctor.user',
            'pet.customer.user',
            'appointment',
        ]);

        return view('medical-records.show', compact('record'));
    }

    /**
     * Edit form
     */
    public function edit(MedicalRecord $record)
    {
        $this->authorizeEdit();

        return view('medical-records.edit', [
            'record'      => $record,
            'appointments'=> Appointment::with(['pet.customer', 'doctor'])->get(),
            'pets'        => Pet::with('customer')->get(),
            'doctors'     => Doctor::active()->get(),
        ]);
    }

    /**
     * Update record
     */
    public function update(Request $request, MedicalRecord $record)
    {
        $this->authorizeEdit();

        $validated = $request->validate([
            'symptoms'       => 'nullable|string',
            'notes'          => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);

        $record->update($validated);

        return redirect()
            ->route('medical-records.show', $record)
            ->with('success', 'Rekam medis berhasil diperbarui!');
    }

    /**
     * List records by pet
     */
    public function byPet(Pet $pet)
    {
        $user = Auth::user();

        if ($user->role === 'customer' && $pet->customer_id !== $user->customer->id) {
            abort(403, 'Akses ditolak.');
        }

        $records = $pet->medicalRecords()
            ->with(['doctor.user', 'appointment'])
            ->latest()
            ->get();

        return view('medical-records.by-pet', compact('records', 'pet'));
    }

    /**
     * Delete record
     */
    public function destroy(MedicalRecord $record)
    {
        $this->authorizeEdit();

        $record->diagnoses()->delete();
        $record->medications()->delete();
        $record->delete();

        return redirect()
            ->route('medical-records')
            ->with('success', 'Rekam medis berhasil dihapus!');
    }

    /**
     * Helper to check permission for edit/delete
     */
    private function authorizeEdit()
    {
        if (Auth::user()->role === 'customer') {
            abort(403, 'Akses ditolak.');
        }
    }
}
