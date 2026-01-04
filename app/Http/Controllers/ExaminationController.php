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
            'weight' => 'required|numeric', // Assuming weight is useful, though model might need update if column exists. Checking MR model, it has temp/heart_rate. Let's stick to MR columns.
            'heart_rate' => 'required|numeric',
            'notes' => 'required|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'medication_fee' => 'nullable|numeric|min:0',
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

        DB::transaction(function () use ($request, $appointment, $validated, &$prescription, &$medicalRecord) {
            // 1. Create Medical Record (use fields present in medical_records table)
            $medicalRecord = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'pet_id' => $appointment->pet_id,
                'doctor_id' => $appointment->doctor_id,
                'symptoms' => implode("; ", array_map(fn($d) => ($d['name'] ?? '') . ' ' . ($d['description'] ?? ''), $validated['diagnoses'])),
                'notes' => "Temperature: {$validated['temperature']}, Heart rate: {$validated['heart_rate']}. " . $validated['notes'],
                'recommendation' => 'Examination and Prescription',
                'record_date' => now(),
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
                        'medical_record_id' => $medicalRecord->id,
                        'medicine_name' => $med['name'],
                        'dosage' => $med['dosage'],
                        'frequency' => $med['frequency'],
                        'duration' => $med['duration'],
                    ]);
                }
            }

            // 4. Update Appointment Status
            $appointment->status = 'completed';
            $appointment->save();

            // 5. Generate Invoice
            // Determine consultation and medication fees; prefer manual inputs from the form when provided
            $doctor = \App\Models\Doctor::find($appointment->doctor_id);
            $defaultConsultation = $doctor && isset($doctor->consultation_fee)
                ? $doctor->consultation_fee
                : (int) env('CONSULTATION_FEE', 150000);
            $medicationUnitFee = (int) env('MEDICATION_FEE', 50000);

            $consultationFee = $request->filled('consultation_fee') ? (float) $request->consultation_fee : $defaultConsultation;
            if ($request->filled('medication_fee')) {
                $medicationFee = (float) $request->medication_fee;
            } else {
                $medicationFee = !empty($request->medications) ? count($request->medications) * $medicationUnitFee : 0;
            }

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
            // end transaction
        });

        // Redirect: if prescription was created, show it; otherwise show medical record
        if (!empty($prescription) && isset($prescription)) {
            return redirect()->route('prescriptions.show', $prescription)->with('success', 'Examination completed successfully.');
        }

        return redirect()->route('medical-records.show', $medicalRecord)->with('success', 'Examination completed successfully.');
    }
}
