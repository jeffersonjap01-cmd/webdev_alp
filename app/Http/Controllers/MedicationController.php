<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicationController extends Controller
{
    /**
     * Display a listing of medications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Medication::with('prescription.pet');
        
        // Doctors see their prescribed medications
        if ($user->role === 'doctor' && $user->doctor) {
            $query->whereHas('prescription', function($q) use ($user) {
                $q->where('doctor_id', $user->doctor->id);
            });
        }
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('dosage', 'like', "%{$search}%");
            });
        }
        
        $medications = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('medications.index', compact('medications'));
    }

    /**
     * Show the form for creating a new medication.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get prescriptions based on user role
        if ($user->role === 'doctor' && $user->doctor) {
            $prescriptions = Prescription::where('doctor_id', $user->doctor->id)
                ->with('pet')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $prescriptions = Prescription::with('pet')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('medications.create', compact('prescriptions'));
    }

    /**
     * Store a newly created medication in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $medication = Medication::create($validated);

        return redirect()
            ->route('medications.show', $medication)
            ->with('success', 'Medication added successfully!');
    }

    /**
     * Display the specified medication.
     */
    public function show(Medication $medication)
    {
        $medication->load('prescription.pet', 'prescription.doctor');
        
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($medication->prescription->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to this medication');
            }
        }
        
        return view('medications.show', compact('medication'));
    }

    /**
     * Show the form for editing the specified medication.
     */
    public function edit(Medication $medication)
    {
        $medication->load('prescription.pet');
        
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($medication->prescription->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to edit this medication');
            }
        }
        
        $prescriptions = Prescription::with('pet')->orderBy('created_at', 'desc')->get();
        
        return view('medications.edit', compact('medication', 'prescriptions'));
    }

    /**
     * Update the specified medication in storage.
     */
    public function update(Request $request, Medication $medication)
    {
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($medication->prescription->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to update this medication');
            }
        }
        
        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $medication->update($validated);

        return redirect()
            ->route('medications.show', $medication)
            ->with('success', 'Medication updated successfully!');
    }

    /**
     * Remove the specified medication from storage.
     */
    public function destroy(Medication $medication)
    {
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($medication->prescription->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to delete this medication');
            }
        }
        
        $medication->delete();

        return redirect()
            ->route('medications.index')
            ->with('success', 'Medication deleted successfully!');
    }
}
