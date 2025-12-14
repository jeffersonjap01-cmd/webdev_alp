<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of diagnoses.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Diagnosis::with('medicalRecord.pet', 'medicalRecord.doctor');
        
        // Doctors see their diagnoses
        if ($user->role === 'doctor' && $user->doctor) {
            $query->whereHas('medicalRecord', function($q) use ($user) {
                $q->where('doctor_id', $user->doctor->id);
            });
        }
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('diagnosis_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $diagnoses = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('diagnoses.index', compact('diagnoses'));
    }

    /**
     * Show the form for creating a new diagnosis.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get medical records based on user role
        if ($user->role === 'doctor' && $user->doctor) {
            $medicalRecords = MedicalRecord::where('doctor_id', $user->doctor->id)
                ->with('pet')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $medicalRecords = MedicalRecord::with('pet')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('diagnoses.create', compact('medicalRecords'));
    }

    /**
     * Store a newly created diagnosis in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'diagnosis_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $diagnosis = Diagnosis::create($validated);

        return redirect()
            ->route('diagnoses.show', $diagnosis)
            ->with('success', 'Diagnosis added successfully!');
    }

    /**
     * Display the specified diagnosis.
     */
    public function show(Diagnosis $diagnosis)
    {
        $diagnosis->load('medicalRecord.pet', 'medicalRecord.doctor');
        
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($diagnosis->medicalRecord->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to this diagnosis');
            }
        }
        
        return view('diagnoses.show', compact('diagnosis'));
    }

    /**
     * Show the form for editing the specified diagnosis.
     */
    public function edit(Diagnosis $diagnosis)
    {
        $diagnosis->load('medicalRecord.pet');
        
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($diagnosis->medicalRecord->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to edit this diagnosis');
            }
        }
        
        $medicalRecords = MedicalRecord::with('pet')->orderBy('created_at', 'desc')->get();
        
        return view('diagnoses.edit', compact('diagnosis', 'medicalRecords'));
    }

    /**
     * Update the specified diagnosis in storage.
     */
    public function update(Request $request, Diagnosis $diagnosis)
    {
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($diagnosis->medicalRecord->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to update this diagnosis');
            }
        }
        
        $validated = $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'diagnosis_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $diagnosis->update($validated);

        return redirect()
            ->route('diagnoses.show', $diagnosis)
            ->with('success', 'Diagnosis updated successfully!');
    }

    /**
     * Remove the specified diagnosis from storage.
     */
    public function destroy(Diagnosis $diagnosis)
    {
        // Check authorization
        $user = Auth::user();
        if ($user->role === 'doctor' && $user->doctor) {
            if ($diagnosis->medicalRecord->doctor_id !== $user->doctor->id) {
                abort(403, 'Unauthorized access to delete this diagnosis');
            }
        }
        
        $diagnosis->delete();

        return redirect()
            ->route('diagnoses.index')
            ->with('success', 'Diagnosis deleted successfully!');
    }
}
