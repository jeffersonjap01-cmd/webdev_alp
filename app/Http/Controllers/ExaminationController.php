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
        // Ensure the appointment belongs to the logged-in doctor
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
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
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'weight' => 'required|numeric', // Assuming weight is useful, though model might need update if column exists. Checking MR model, it has temp/heart_rate. Let's stick to MR columns.
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

        DB::transaction(function () use ($request, $appointment, $validated) {
            // 1. Create Medical Record
            $medicalRecord = MedicalRecord::create([
                'pet_id' => $appointment->pet_id,
                'doctor_id' => $appointment->doctor_id,
                'date' => now(),
                'temperature' => $validated['temperature'],
                'heart_rate' => $validated['heart_rate'],
                'notes' => $validated['notes'],
                'diagnosis' => $validated['diagnoses'][0]['name'], // Fallback for single column if exists, but we use relation
                'treatment' => 'Examination and Prescription', // Default description
            ]);

            // 2. Save Diagnoses
            foreach ($request->diagnoses as $diag) {
                Diagnosis::create([
                    'medical_record_id' => $medicalRecord->id,
                    'diagnosis_name' => $diag['name'],
                    'description' => $diag['description'],
                ]);
            }

            // 3. Create Prescription if medications exist
            if (!empty($request->medications)) {
                $prescription = Prescription::create([
                    'pet_id' => $appointment->pet_id,
                    'doctor_id' => $appointment->doctor_id,
                    'medical_record_id' => $medicalRecord->id,
                    'date' => now(),
                    'status' => 'active',
                    // 'diagnosis' => $medicalRecord->diagnosis, // Optional redundancy
                    'instructions' => 'Follow medication instructions carefully.',
                ]);

                foreach ($request->medications as $med) {
                    Medication::create([
                        'prescription_id' => $prescription->id,
                        'name' => $med['name'],
                        'dosage' => $med['dosage'],
                        'frequency' => $med['frequency'],
                        'duration' => $med['duration'],
                        'notes' => $med['instructions'] ?? null,
                    ]);
                }
            }

            // 4. Update Appointment Status
            $appointment->status = 'completed';
            $appointment->save();

            // 5. Generate Invoice
            // Base consultation fee + fee per medication? Let's simplify: Base fee 150000 + 50000 per med
            $consultationFee = 150000;
            $medicationFee = !empty($request->medications) ? count($request->medications) * 50000 : 0;

            $subtotal = $consultationFee + $medicationFee;
            $tax = $subtotal * 0.11; // 11% VAT
            $total = $subtotal + $tax;

            $invoice = Invoice::create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'pet_id' => $appointment->pet_id,
                'date' => now(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => 0,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Create Invoice Items logic could go here if InvoiceItem model exists, but trying to keep simple based on request scope.
        });

        return redirect()->route('home')->with('success', 'Examination completed successfully.');
    }
}
