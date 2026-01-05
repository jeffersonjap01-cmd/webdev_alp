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

        $query = MedicalRecord::with(['pet.user', 'doctor.user', 'appointment', 'diagnoses']);

        // Customer sees only their pets (role can be 'customer' or 'user')
        if (in_array($user->role, ['customer', 'user'])) {
            $query->whereHas('pet', fn($q) =>
                $q->where('user_id', $user->id)
            );
        }

        // Doctor sees only their own patients
        if ($user->role === 'doctor' && $user->doctor) {
            $query->where('doctor_id', $user->doctor->id);
        }

        // Apply filters
        if ($request->has('pet') && $request->pet) {
            $query->where('pet_id', $request->pet);
        }
        
        if ($request->has('doctor') && $request->doctor) {
            $query->where('doctor_id', $request->doctor);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('record_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('record_date', '<=', $request->date_to);
        }

        $records = $query->latest()->paginate(15);

        // Load invoices for payment status check
        $records->load(['appointment' => function($q) {
            $q->with('invoice');
        }]);

        return view('medical-records.index', [
            'medicalRecords' => $records,
        ]);
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
        $selectedAppointment = null;
        $examinationData = session('examination_data');
        
        // Get appointment from request or examination data
        if ($request->appointment_id) {
            $selectedAppointment = Appointment::with(['pet.customer', 'doctor'])->find($request->appointment_id);
        } elseif ($examinationData && isset($examinationData['appointment_id'])) {
            $selectedAppointment = Appointment::with(['pet.customer', 'doctor'])->find($examinationData['appointment_id']);
        }

        return view('medical-records.create', [
            'appointments' => Appointment::with(['pet.customer', 'doctor'])->get(),
            'pets'         => Pet::with('customer')->get(),
            'doctors'      => Doctor::active()->get(),
            'selectedAppointment' => $selectedAppointment,
            'examinationData' => $examinationData,
        ]);
    }

    /**
     * Store medical record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'doctor_id'      => 'nullable|exists:doctors,id',
            'pet_id'         => 'required|exists:pets,id',
            'symptoms'       => 'nullable|string',
            'diagnosis'      => 'nullable|string',
            'treatment'      => 'nullable|string',
            'notes'          => 'nullable|string',
            'recommendation' => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'diagnoses'      => 'nullable|array',
            'medications'    => 'nullable|array',
        ]);
        
        // Get doctor_id from appointment if not provided
        if (empty($validated['doctor_id'])) {
            $appointment = Appointment::find($validated['appointment_id']);
            if ($appointment && $appointment->doctor_id) {
                $validated['doctor_id'] = $appointment->doctor_id;
            } else {
                // Fallback to current user's doctor if they are a doctor
                $user = Auth::user();
                if ($user && $user->role === 'doctor' && $user->doctor) {
                    $validated['doctor_id'] = $user->doctor->id;
                } else {
                    return back()->withErrors(['doctor_id' => 'Doctor is required.'])->withInput();
                }
            }
        }

        // Get examination data from session if exists
        $examinationData = session('examination_data', []);
        
        // Ensure $record exists after transaction
        $record = null;

        // Use transaction to ensure related records are created together
        DB::transaction(function () use ($validated, $examinationData, &$record) {
            // Merge examination data if exists
            $recordData = $validated;
            if (!empty($examinationData)) {
                $recordData['symptoms'] = $recordData['symptoms'] ?? implode("; ", array_map(fn($d) => ($d['name'] ?? '') . ': ' . ($d['description'] ?? ''), $examinationData['diagnoses'] ?? []));
                $recordData['notes'] = $recordData['notes'] ?? ("Temperature: " . ($examinationData['temperature'] ?? '') . ", Heart rate: " . ($examinationData['heart_rate'] ?? '') . ". " . ($examinationData['notes'] ?? ''));
            }
            
            $record = MedicalRecord::create($recordData);

            // Insert diagnoses from form or examination data
            $diagnosesToInsert = !empty($validated['diagnoses']) ? $validated['diagnoses'] : ($examinationData['diagnoses'] ?? []);
            if (!empty($diagnosesToInsert)) {
                foreach ($diagnosesToInsert as $d) {
                    Diagnosis::create([
                        'medical_record_id' => $record->id,
                        'diagnosis_name'    => $d['name'] ?? $d['diagnosis_name'] ?? '',
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

            // Generate invoice using consultation fee from form
            $consultationFee = (float) $validated['consultation_fee'];
            $medicationUnitFee = (int) env('MEDICATION_FEE', 50000);
            $medicationsToCount = !empty($validated['medications']) ? $validated['medications'] : ($examinationData['medications'] ?? []);
            $medicationFee = !empty($medicationsToCount) ? count($medicationsToCount) * $medicationUnitFee : 0;

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

        // Clear examination data from session
        session()->forget('examination_data');
        
        return redirect()
            ->route('medical-records.show', $record)
            ->with('success', 'Rekam medis berhasil dibuat! Silakan buat resep obat.');
    }

    /**
     * Show a medical record
     */
    public function show(MedicalRecord $record)
    {
        $user = Auth::user();

        // Customer can only see their own pet (role can be 'customer' or 'user')
        if (in_array($user->role, ['customer', 'user'])) {
            // Check if customer profile exists
            if (!$user->customer) {
                abort(403, 'Profil customer tidak ditemukan. Silakan hubungi administrator.');
            }
            
            // Check if pet exists
            if (!$record->pet) {
                abort(403, 'Data hewan peliharaan tidak ditemukan.');
            }
            
            // Check if pet belongs to the customer
            if ($record->pet->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk rekam medis ini. Rekam medis ini bukan milik hewan peliharaan Anda.');
            }
            
            // Check if invoice is paid - customer can only view paid medical records
            $invoice = null;
            if ($record->appointment_id) {
                // Use fresh() to ensure we get the latest status from database
                $invoice = \App\Models\Invoice::where('appointment_id', $record->appointment_id)->first();
                if ($invoice) {
                    $invoice->refresh(); // Refresh to get latest status
                }
            }
            
            // If no invoice, customer can still view (maybe old record before invoice system)
            // But if invoice exists and not paid, block access
            if ($invoice) {
                if ($invoice->status !== 'paid') {
                    return redirect()->route('invoices.show', $invoice)
                        ->with('error', 'Anda harus membayar tagihan terlebih dahulu untuk melihat rekam medis. Status pembayaran saat ini: ' . ucfirst($invoice->status));
                }
            }
        }

        $record->load([
            'diagnoses',
            'medications',
            'doctor.user',
            'pet.user',
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

        if (in_array($user->role, ['customer', 'user']) && $pet->user_id !== $user->id) {
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
        if (in_array(Auth::user()->role, ['customer', 'user'])) {
            abort(403, 'Akses ditolak.');
        }
    }

    /**
     * Export medical record to PDF
     */
    public function exportPdf(MedicalRecord $record)
    {
        $user = Auth::user();

        // Customer can only see their own pet (role can be 'customer' or 'user')
        if (in_array($user->role, ['customer', 'user'])) {
            if (!$record->pet || $record->pet->user_id !== $user->id) {
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
