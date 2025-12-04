<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\Medication;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
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

        return response()->json([
            'message' => 'Medical record created',
            'record' => $record->load('diagnoses', 'medications')
        ]);
    }

    public function show($id)
    {
        return MedicalRecord::with('diagnoses', 'medications', 'doctor', 'pet')->findOrFail($id);
    }
}
