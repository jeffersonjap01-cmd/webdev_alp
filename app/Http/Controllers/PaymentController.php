<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Middleware sudah di-route, jadi constructor kosong
    public function __construct() {}

    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Payment::with(['medicalRecord.pet.customer']);

        // Jika customer → hanya lihat tagihan hewannya
        if ($user->role === 'customer' && $user->customer) {
            $query->whereHas('medicalRecord.pet', function ($q) use ($user) {
                $q->where('customer_id', $user->customer->id);
            });
        }

        // Filter status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show create payment form (Admin only)
     */
    public function create()
    {
        $medicalRecords = MedicalRecord::with(['pet.customer', 'doctor'])->get();
        return view('payments.create', compact('medicalRecords'));
    }

    /**
     * Store payment (Admin only)
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'consultation_fee'  => 'required|numeric|min:0',
            'medication_fee'    => 'required|numeric|min:0',
            'other_fee'         => 'nullable|numeric|min:0',
        ]);

        $total = $request->consultation_fee
               + $request->medication_fee
               + ($request->other_fee ?? 0);

        $payment = Payment::create([
            'medical_record_id' => $request->medical_record_id,
            'consultation_fee'  => $request->consultation_fee,
            'medication_fee'    => $request->medication_fee,
            'other_fee'         => $request->other_fee ?? 0,
            'total_amount'      => $total,
            'status'            => 'unpaid',
        ]);

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Tagihan berhasil dibuat!');
    }

    /**
     * Show payment details
     */
    public function show(Payment $payment)
    {
        $user = Auth::user();

        // Customer hanya bisa lihat tagihan hewannya
        if ($user->role === 'customer') {
            if ($payment->medicalRecord->pet->customer_id !== $user->customer->id) {
                abort(403, 'Anda tidak memiliki akses melihat tagihan ini.');
            }
        }

        $payment->load(['medicalRecord.pet.customer', 'medicalRecord.doctor']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Payment form for customer
     */
    public function pay(Payment $payment)
    {
        $user = Auth::user();

        if ($user->role !== 'customer') {
            abort(403, 'Hanya customer yang bisa membayar tagihan.');
        }

        if ($payment->medicalRecord->pet->customer_id !== $user->customer->id) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        if ($payment->status === 'paid') {
            return redirect()->route('payments.show', $payment)
                             ->with('error', 'Tagihan sudah dibayar.');
        }

        $payment->load(['medicalRecord.pet.customer']);

        return view('payments.pay', compact('payment'));
    }

    /**
     * Upload payment proof (Customer only)
     */
    public function uploadProof(Request $request, Payment $payment)
    {
        $user = Auth::user();

        if ($user->role !== 'customer') {
            abort(403, 'Hanya customer yang dapat upload bukti pembayaran.');
        }

        if ($payment->medicalRecord->pet->customer_id !== $user->customer->id) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $request->validate([
            'proof_image' => 'required|image|max:2048',
        ]);

        $path = $request->file('proof_image')->store('payment_proofs', 'public');

        $payment->update([
            'proof_image' => $path,
            'status' => 'pending_verification',
        ]);

        return redirect()->route('payments.show', $payment)
                         ->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    /**
     * Mark as paid (Admin only)
     */
    public function markPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('payments.show', $payment)
                         ->with('success', 'Pembayaran ditandai sebagai lunas!');
    }

    /**
     * Mark as rejected (Admin only)
     */
    public function markRejected(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('payments.show', $payment)
                         ->with('success', 'Pembayaran berhasil ditolak!');
    }

    /**
     * Delete payment (Admin only)
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments')
                         ->with('success', 'Tagihan berhasil dihapus!');
    }
}
