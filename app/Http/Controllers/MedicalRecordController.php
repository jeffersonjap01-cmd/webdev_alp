<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\Medication;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Doctor;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,vet')->except(['index', 'show', 'byPet']);
    }

    /**
     * Display a listing of medical records
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::query()->with(['pet.owner', 'doctor', 'appointment']);

        // Filter by pet (for owner role)
        if (auth()->user()->role === 'owner' && auth()->user()->customer) {
            $query->whereHas('pet', function($q) {
                $q->where('customer_id', auth()->user()->customer->id);
            });
        }

        // Filter by doctor (for vet role)
        if (auth()->user()->role === 'vet' && auth()->user()->doctor) {
            $query->where('doctor_id', auth()->user()->doctor->id);
        }

        $records = $query->latest('created_at')->paginate(15);

        return view('medical-records.index', compact('records'));
    }

    /**
     * Show the form for creating a new medical record
     */
    public function create(Request $request)
    {
        $appointments = Appointment::with(['pet.owner', 'doctor'])->get();
        $pets = Pet::with('owner')->get();
        $doctors = Doctor::where('is_active', true)->get();

        // Pre-select appointment if provided
        $selectedAppointment = null;
        if ($request->has('appointment_id')) {
            $selectedAppointment = Appointment::with(['pet.owner', 'doctor'])
                ->find($request->appointment_id);
        }

        return view('medical-records.create', compact('appointments', 'pets', 'doctors', 'selectedAppointment'));
    }

    /**
     * Store a newly created medical record
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'pet_id'         => 'required|exists:pets,id',
            'symptoms'       => 'nullable|string',
            'notes'          => 'nullable|string',
            'recommendation' => 'nullable|string',
            'diagnoses'      => 'nullable|array',
            'medications'    => 'nullable|array',
        ]);

        // Create main record
        $record = MedicalRecord::create($request->only([
            'appointment_id', 'doctor_id', 'pet_id', 
            'symptoms', 'notes', 'recommendation'
        ]));

        // Add diagnoses
        if ($request->diagnoses) {
            foreach ($request->diagnoses as $diag) {
                Diagnosis::create([
                    'medical_record_id' => $record->id,
                    'diagnosis_name' => $diag['name'],
                    'description' => $diag['description'] ?? null,
                ]);
            }
        }

        // Add medications
        if ($request->medications) {
            foreach ($request->medications as $med) {
                Medication::create([
                    'medical_record_id' => $record->id,
                    'medicine_name' => $med['name'],
                    'dosage' => $med['dosage'] ?? null,
                    'frequency' => $med['frequency'] ?? null,
                    'duration' => $med['duration'] ?? null,
                ]);
            }
        }

        return redirect()->route('medical-records.show', $record)->with('success', 'Rekam medis berhasil dibuat!');
    }

    /**
     * Display the specified medical record
     */
    public function show(MedicalRecord $record)
    {
        // Check access - owners can only see their pet's medical records
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->customer->id !== $record->pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat rekam medis ini.');
            }
        }

        $record->load(['diagnoses', 'medications', 'doctor', 'pet.owner', 'appointment']);

        return view('medical-records.show', compact('record'));
    }

    /**
     * Show the form for editing the specified medical record
     */
    public function edit(MedicalRecord $record)
    {
        // Check access - only admin and vet can edit
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit rekam medis.');
        }

        $appointments = Appointment::with(['pet.owner', 'doctor'])->get();
        $pets = Pet::with('owner')->get();
        $doctors = Doctor::where('is_active', true)->get();

        return view('medical-records.edit', compact('record', 'appointments', 'pets', 'doctors'));
    }

    /**
     * Update the specified medical record
     */
    public function update(Request $request, MedicalRecord $record)
    {
        // Check access - only admin and vet can update
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit rekam medis.');
        }

        $request->validate([
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);

        $record->update($request->only(['symptoms', 'notes', 'recommendation']));

        return redirect()->route('medical-records.show', $record)->with('success', 'Rekam medis berhasil diperbarui!');
    }

    /**
     * Get medical records by pet
     */
    public function byPet(Request $request, Pet $pet)
    {
        // Check ownership for owners
        if (auth()->user()->role === 'owner') {
            if ($pet->customer_id !== auth()->user()->customer->id) {
                abort(403, 'Hewan peliharaan tidak ditemukan atau akses ditolak.');
            }
        }

        $records = MedicalRecord::where('pet_id', $pet->id)
            ->with(['doctor', 'appointment'])
            ->latest('created_at')
            ->get();

        return view('medical-records.by-pet', compact('records', 'pet'));
    }

    /**
     * Remove the specified medical record
     */
    public function destroy(MedicalRecord $record)
    {
        // Check access - only admin and vet can delete
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus rekam medis.');
        }

        // Delete associated diagnoses and medications first
        $record->diagnoses()->delete();
        $record->medications()->delete();
        $record->delete();

        return redirect()->route('medical-records')->with('success', 'Rekam medis berhasil dihapus!');
    }
}
