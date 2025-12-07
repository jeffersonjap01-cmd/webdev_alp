<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Display a listing of payments
     */
    public function index(Request $request)
    {
        $query = Payment::query()->with(['medicalRecord.pet.owner']);

        // Filter by owner (for owner role)
        if (auth()->user()->role === 'owner' && auth()->user()->owner) {
            $query->whereHas('medicalRecord.pet', function($q) {
                $q->where('customer_id', auth()->user()->owner->id);
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest('created_at')->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment (Admin only)
     */
    public function create()
    {
        $medicalRecords = MedicalRecord::with(['pet.owner', 'doctor'])->get();
        return view('payments.create', compact('medicalRecords'));
    }

    /**
     * Store a newly created payment (Admin only)
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

        return redirect()->route('payments.show', $payment)->with('success', 'Tagihan berhasil dibuat!');
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        // Check access - owners can only see their own payments
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id !== $payment->medicalRecord->pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat tagihan ini.');
            }
        }

        $payment->load(['medicalRecord.pet.owner', 'medicalRecord.doctor']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show payment form for owner to pay
     */
    public function pay(Payment $payment)
    {
        // Check access - only owner can pay their bills
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Hanya pemilik yang dapat melakukan pembayaran.');
        }

        if (auth()->user()->owner->id !== $payment->medicalRecord->pet->customer_id) {
            abort(403, 'Anda tidak memiliki akses untuk membayar tagihan ini.');
        }

        if ($payment->status === 'paid') {
            return redirect()->route('payments.show', $payment)->with('error', 'Tagihan sudah dibayar.');
        }

        $payment->load(['medicalRecord.pet.owner']);
        return view('payments.pay', compact('payment'));
    }

    /**
     * Upload payment proof (Owner only)
     */
    public function uploadProof(Request $request, Payment $payment)
    {
        // Check access - only owner can upload proof
        if (auth()->user()->role !== 'owner') {
            abort(403, 'Hanya pemilik yang dapat mengupload bukti pembayaran.');
        }

        if (auth()->user()->owner->id !== $payment->medicalRecord->pet->customer_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupload bukti pembayaran.');
        }

        if ($payment->status === 'paid') {
            return back()->with('error', 'Tagihan sudah dibayar.');
        }

        $request->validate([
            'proof_image' => 'required|image|max:2048'
        ]);

        $path = $request->file('proof_image')->store('payment_proofs', 'public');

        $payment->update([
            'proof_image' => $path,
            'status' => 'pending_verification'
        ]);

        return redirect()->route('payments.show', $payment)->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    /**
     * Mark payment as paid (Admin only)
     */
    public function markPaid(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        return redirect()->route('payments.show', $payment)->with('success', 'Pembayaran berhasil ditandai sebagai lunas!');
    }

    /**
     * Mark payment as rejected (Admin only)
     */
    public function markRejected(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->route('payments.show', $payment)->with('success', 'Pembayaran berhasil ditolak!');
    }

    /**
     * Remove the specified payment (Admin only)
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments')->with('success', 'Tagihan berhasil dihapus!');
    }
}
