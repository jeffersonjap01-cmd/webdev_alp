<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Medication;
use App\Models\Pet;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    // Middleware handled in routes

    /**
     * Display a listing of prescriptions
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Prescription::with(['pet.user', 'doctor', 'medications']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Customer sees only their prescriptions
        if ($user->role === 'customer') {
            $query->whereHas('pet', fn($q) =>
                $q->where('user_id', $user->id)
            );
        }

        // Doctor sees only prescriptions they created
        if ($user->role === 'doctor' && $user->doctor) {
            $query->where('doctor_id', $user->doctor->id);
        }

        $prescriptions = $query->latest('date')->paginate(15);

        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new prescription
     */
    public function create()
    {
        return view('prescriptions.create', [
            'pets'    => Pet::with('user')->get(),
            'doctors' => Doctor::active()->get(),
        ]);
    }

    /**
     * Store a newly created prescription
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id'              => 'required|exists:pets,id',
            'doctor_id'           => 'required|exists:doctors,id',
            'medical_record_id'   => 'nullable|exists:medical_records,id',
            'date'                => 'required|date',
            'diagnosis'           => 'required|string',
            'instructions'        => 'required|string',
            'medications'         => 'required|array|min:1',
            'medications.*.name'  => 'required|string|max:255',
            'medications.*.dosage'=> 'required|string|max:255',
            'medications.*.frequency'=> 'required|string|max:255',
            'medications.*.duration'=> 'required|string|max:255',
            'medications.*.notes' => 'nullable|string',
        ]);

        $prescription = Prescription::create([
            'pet_id'            => $validated['pet_id'],
            'doctor_id'         => $validated['doctor_id'],
            'medical_record_id' => $validated['medical_record_id'] ?? null,
            'date'              => $validated['date'],
            'diagnosis'         => $validated['diagnosis'],
            'instructions'      => $validated['instructions'],
            'status'            => 'active',
        ]);

        foreach ($validated['medications'] as $medication) {
            $prescription->medications()->create($medication);
        }

        return redirect()
            ->route('prescriptions.show', $prescription)
            ->with('success', 'Resep berhasil dibuat!');
    }

    /**
     * Display the specified prescription
     */
    public function show(Prescription $prescription)
    {
        $user = Auth::user();

        // Customer only sees their prescriptions
        if ($user->role === 'customer') {
            if ($prescription->pet->user_id !== $user->id) {
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
        $user = Auth::user();

        // Only doctor/admin can edit
        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit resep.');
        }

        return view('prescriptions.edit', [
            'prescription' => $prescription,
            'pets'         => Pet::with('user')->get(),
            'doctors'      => Doctor::active()->get(),
        ]);
    }

    /**
     * Update the specified prescription
     */
    public function update(Request $request, Prescription $prescription)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit resep.');
        }

        $validated = $request->validate([
            'diagnosis'     => 'sometimes|string',
            'instructions'  => 'sometimes|string',
        ]);

        $prescription->update($validated);

        return redirect()
            ->route('prescriptions.show', $prescription)
            ->with('success', 'Resep berhasil diperbarui!');
    }

    /**
     * Update prescription status
     */
    public function updateStatus(Request $request, Prescription $prescription)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status resep.');
        }

        $validated = $request->validate([
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $prescription->update(['status' => $validated['status']]);

        return redirect()
            ->route('prescriptions.show', $prescription)
            ->with('success', 'Status resep berhasil diperbarui!');
    }

    /**
     * Get prescriptions by pet
     */
    public function byPet(Request $request, $petId)
    {
        $user = Auth::user();

        // Users can only see their pets' prescriptions
        if ($user->role === 'user') {
            $pet = Pet::where('id', $petId)
                ->where('user_id', $user->id)
                ->first();

            if (!$pet) {
                abort(403, 'Hewan peliharaan tidak ditemukan atau akses ditolak.');
            }
        }

        $prescriptions = Prescription::where('pet_id', $petId)
            ->with(['doctor', 'medications'])
            ->latest('date')
            ->get();

        return view('prescriptions.by-pet', compact('prescriptions'));
    }

    /**
     * Remove the specified prescription
     */
    public function destroy(Prescription $prescription)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus resep.');
        }

        $prescription->medications()->delete();
        $prescription->delete();

        return redirect()
            ->route('prescriptions')
            ->with('success', 'Resep berhasil dihapus!');
    }
}
