<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\Medication;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Doctor;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    

    /**
     * List all medical records
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = MedicalRecord::with(['pet.customer.user', 'doctor.user', 'appointment', 'diagnoses']);

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

        return view('medical-records.index', [
            'medicalRecords' => $records,
        ]);
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
            'diagnosis'      => 'nullable|string',
            'treatment'      => 'nullable|string',
            'notes'          => 'nullable|string',
            'recommendation' => 'nullable|string',
            'diagnoses'      => 'nullable|array',
            'medications'    => 'nullable|array',
        ]);

        // Ensure $record exists after transaction
        $record = null;

        // Use transaction to ensure related records are created together
        DB::transaction(function () use ($validated, &$record) {
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

            // Update appointment status to completed
            $appointment = Appointment::find($validated['appointment_id']);
            if ($appointment) {
                $appointment->status = 'completed';
                $appointment->save();
            }

            // Generate basic invoice: base consultation + per-medication fee
            // Use doctor's configured consultation fee if available, otherwise fallback to env/default
            $doctor = \App\Models\Doctor::find($validated['doctor_id']);
            $consultationFee = $doctor && isset($doctor->consultation_fee)
                ? $doctor->consultation_fee
                : (int) env('CONSULTATION_FEE', 150000);
            $medicationUnitFee = (int) env('MEDICATION_FEE', 50000);
            $medicationFee = !empty($validated['medications']) ? count($validated['medications']) * $medicationUnitFee : 0;

            $subtotal = $consultationFee + $medicationFee;
            $tax = $subtotal * 0.11; // 11% VAT
            $total = $subtotal + $tax;

            Invoice::create([
                'appointment_id' => $validated['appointment_id'],
                'user_id'        => $appointment->user_id ?? null,
                'pet_id'         => $validated['pet_id'],
                'date'           => now(),
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'discount'       => 0,
                'total'          => $total,
                'status'         => 'pending',
            ]);
        });

        // Ensure the record was actually persisted (transaction may have rolled back)
        $record = MedicalRecord::find($record->id);
        if (! $record) {
            return redirect()
                ->route('medical-records')
                ->with('error', 'Gagal membuat rekam medis. Silakan coba lagi.');
        }

        return redirect()
            ->route('prescriptions.create', ['medical_record_id' => $record->id])
            ->with('success', 'Rekam medis berhasil dibuat! Silakan buat resep obat.');
    }

    /**
     * Show a medical record
     */
    public function show(MedicalRecord $record)
    {
        $user = Auth::user();

        // Customer can only see their own pet
        if ($user->role === 'customer') {
            if (!$user->customer || ! $record->pet || $record->pet->customer_id !== $user->customer->id) {
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
            'diagnosis'      => 'nullable|string',
            'treatment'      => 'nullable|string',
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

        return view('medical-records.by-pet', [
            'medicalRecords' => $records,
            'pet' => $pet,
        ]);
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

    /**
     * Export medical record to PDF
     */
    public function exportPdf(MedicalRecord $record)
    {
        $user = Auth::user();

        // Customer can only see their own pet
        if ($user->role === 'customer') {
            if (!$user->customer || !$record->pet || $record->pet->customer_id !== $user->customer->id) {
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

        $pdf = Pdf::loadView('medical-records.pdf', [
            'medicalRecord' => $record
        ]);

        $filename = 'Medical_Record_' . $record->pet->name . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
