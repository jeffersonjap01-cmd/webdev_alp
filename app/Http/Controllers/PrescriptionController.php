<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Medication;
use App\Models\Pet;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Display a listing of prescriptions
     */
    public function index(Request $request)
    {
        $query = Prescription::query()->with(['pet.owner', 'doctor', 'medications']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by pet (for owner role)
        if (auth()->user()->role === 'owner' && auth()->user()->owner) {
            $query->whereHas('pet', function($q) {
                $q->where('customer_id', auth()->user()->owner->id);
            });
        }

        // Filter by doctor (for vet role)
        if (auth()->user()->role === 'vet' && auth()->user()->doctor) {
            $query->where('doctor_id', auth()->user()->doctor->id);
        }

        $prescriptions = $query->latest('date')->paginate(15);

        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new prescription
     */
    public function create()
    {
        $pets = Pet::with('owner')->get();
        $doctors = Doctor::where('is_active', true)->get();
        return view('prescriptions.create', compact('pets', 'doctors'));
    }

    /**
     * Store a newly created prescription
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'doctor_id' => 'required|exists:doctors,id',
            'medical_record_id' => 'nullable|exists:medical_records,id',
            'date' => 'required|date',
            'diagnosis' => 'required|string',
            'instructions' => 'required|string',
            'medications' => 'required|array|min:1',
            'medications.*.name' => 'required|string|max:255',
            'medications.*.dosage' => 'required|string|max:255',
            'medications.*.frequency' => 'required|string|max:255',
            'medications.*.duration' => 'required|string|max:255',
            'medications.*.notes' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'pet_id' => $validated['pet_id'],
            'doctor_id' => $validated['doctor_id'],
            'medical_record_id' => $validated['medical_record_id'] ?? null,
            'date' => $validated['date'],
            'diagnosis' => $validated['diagnosis'],
            'instructions' => $validated['instructions'],
            'status' => 'active',
        ]);

        // Create medications
        foreach ($validated['medications'] as $medication) {
            $prescription->medications()->create($medication);
        }

        return redirect()->route('prescriptions.show', $prescription)->with('success', 'Resep berhasil dibuat!');
    }

    /**
     * Display the specified prescription
     */
    public function show(Prescription $prescription)
    {
        // Check access - owners can only see their pet's prescriptions
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id !== $prescription->pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat resep ini.');
            }
        }

        return view('prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified prescription
     */
    public function edit(Prescription $prescription)
    {
        // Check access - only admin and vet can edit
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit resep.');
        }

        $pets = Pet::with('owner')->get();
        $doctors = Doctor::where('is_active', true)->get();
        return view('prescriptions.edit', compact('prescription', 'pets', 'doctors'));
    }

    /**
     * Update the specified prescription
     */
    public function update(Request $request, Prescription $prescription)
    {
        // Check access - only admin and vet can update
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit resep.');
        }

        $validated = $request->validate([
            'diagnosis' => 'sometimes|string',
            'instructions' => 'sometimes|string',
        ]);

        $prescription->update($validated);

        return redirect()->route('prescriptions.show', $prescription)->with('success', 'Resep berhasil diperbarui!');
    }

    /**
     * Update prescription status
     */
    public function updateStatus(Request $request, Prescription $prescription)
    {
        // Check access - only admin and vet can update status
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status resep.');
        }

        $validated = $request->validate([
            'status' => 'required|in:active,completed,cancelled'
        ]);

        $prescription->update(['status' => $validated['status']]);

        return redirect()->route('prescriptions.show', $prescription)->with('success', 'Status resep berhasil diperbarui!');
    }

    /**
     * Get prescriptions by pet
     */
    public function byPet(Request $request, $petId)
    {
        $query = Prescription::where('pet_id', $petId)
            ->with(['doctor', 'medications']);

        // Check ownership for owners
        if (auth()->user()->role === 'owner') {
            $pet = Pet::where('id', $petId)
                ->where('customer_id', auth()->user()->owner->id)
                ->first();

            if (!$pet) {
                abort(403, 'Hewan peliharaan tidak ditemukan atau akses ditolak.');
            }
        }

        $prescriptions = $query->latest('date')->get();

        return view('prescriptions.by-pet', compact('prescriptions'));
    }

    /**
     * Remove the specified prescription
     */
    public function destroy(Prescription $prescription)
    {
        // Check access - only admin and vet can delete
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus resep.');
        }

        // Delete associated medications first
        $prescription->medications()->delete();
        $prescription->delete();

        return redirect()->route('prescriptions')->with('success', 'Resep berhasil dihapus!');
    }
}