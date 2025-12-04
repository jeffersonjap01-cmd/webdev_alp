<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * ADMIN membuat tagihan
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'consultation_fee'  => 'required|numeric',
            'medication_fee'    => 'required|numeric',
            'other_fee'         => 'nullable|numeric',
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

        return response()->json([
            'message' => 'Payment created successfully',
            'payment' => $payment
        ]);
    }

    /**
     * CUSTOMER upload bukti pembayaran QRIS
     */
    public function uploadProof(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'proof_image' => 'required|image|max:2048'
        ]);

        $path = $request->file('proof_image')->store('payment_proofs', 'public');

        $payment->proof_image = $path;
        $payment->save();

        return response()->json([
            'message' => 'Payment proof uploaded',
            'payment' => $payment
        ]);
    }

    /**
     * ADMIN menandai pembayaran sudah LUNAS
     */
    public function markPaid($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->status = 'paid';
        $payment->save();

        return response()->json([
            'message' => 'Payment marked as paid',
            'payment' => $payment
        ]);
    }

    /**
     * lihat detail pembayaran
     */
    public function show($id)
    {
        return Payment::with('medicalRecord')->findOrFail($id);
    }
}
