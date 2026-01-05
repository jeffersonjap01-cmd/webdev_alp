<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExaminationController extends Controller
{
    public function show(Appointment $appointment)
    {
        // Ensure the user is a doctor and the appointment belongs to them
        if (! auth()->user() || ! auth()->user()->doctor || $appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403, 'Unauthorized access to this appointment.');
        }

        // Update status to in_progress if it was accepted/scheduled
        if (in_array($appointment->status, ['accepted', 'scheduled'])) {
            $appointment->status = 'in_progress';
            $appointment->save();
        }

        // load relationships
        $appointment->load(['pet.medicalRecords', 'pet.user']);

        return view('doctor.examination.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        // Ensure the user is a doctor and the appointment belongs to them
        if (! auth()->user() || ! auth()->user()->doctor || $appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403, 'Unauthorized access to this appointment.');
        }

        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'weight' => 'nullable|numeric',
            'heart_rate' => 'required|numeric',
            'notes' => 'required|string',
            'diagnoses' => 'required|array|min:1',
            'diagnoses.*.name' => 'required|string',
            'diagnoses.*.description' => 'required|string',
            'medications' => 'nullable|array',
            'medications.*.name' => 'required_with:medications|string',
            'medications.*.dosage' => 'required_with:medications|string',
            'medications.*.frequency' => 'required_with:medications|string',
            'medications.*.duration' => 'required_with:medications|string',
            'medications.*.instructions' => 'nullable|string',
        ]);

        $prescription = null;
        $medicalRecord = null;

        // Store examination data in session for medical record creation
        session([
            'examination_data' => [
                'appointment_id' => $appointment->id,
                'temperature' => $validated['temperature'],
                'heart_rate' => $validated['heart_rate'],
                'weight' => $validated['weight'],
                'notes' => $validated['notes'],
                'diagnoses' => $request->diagnoses,
                'medications' => $request->medications ?? [],
            ]
        ]);

        // Update appointment status to in_progress (will be completed after medical record creation)
        $appointment->status = 'in_progress';
        $appointment->save();

        // Redirect to medical records create page to set consultation fee and finalize
        return redirect()->route('medical-records.create', ['appointment_id' => $appointment->id])
            ->with('success', 'Examination completed! Please set the consultation fee and finalize the medical record.');
    }
}
