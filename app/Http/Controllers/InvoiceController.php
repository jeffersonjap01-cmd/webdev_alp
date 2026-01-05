<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     */
    public function index()
    {
        $user = Auth::user();
        $query = Invoice::with(['user', 'pet', 'appointment'])->latest();

        // Customer sees only their own invoices
        if ($user->role === 'customer') {
            $query->where('user_id', $user->id);
        }

        $invoices = $query->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Display the specified invoice
     */
    public function show(Invoice $invoice)
    {
        $user = Auth::user();

        // Customer can only view their own invoices
        if ($user->role === 'customer' && $invoice->user_id !== $user->id) {
            abort(403, 'Unauthorized to view this invoice.');
        }

        $invoice->load(['user', 'pet', 'appointment']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Upload QR code for invoice (Admin only)
     */
    public function uploadQrCode(Request $request, Invoice $invoice)
    {
        $request->validate([
            'qr_code_image' => 'required|image|max:2048',
        ]);

        // Delete old QR code if exists
        if ($invoice->qr_code_image) {
            Storage::disk('public')->delete($invoice->qr_code_image);
        }

        // Store new QR code
        $path = $request->file('qr_code_image')->store('qr_codes', 'public');
        
        $invoice->update([
            'qr_code_image' => $path,
        ]);

        return redirect()->back()->with('success', 'QR Code berhasil diupload!');
    }

    /**
     * Approve payment (Admin only)
     */
    public function approvePayment(Invoice $invoice)
    {
        $user = Auth::user();
        
        // Only admin can approve payments
        if ($user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat menyetujui pembayaran.');
        }
        
        // Mark invoice as paid
        $invoice->markAsPaid('QRIS', 'QRIS Payment');
        
        // Refresh invoice to ensure status is updated
        $invoice->refresh();

        return redirect()->back()->with('success', 'Pembayaran berhasil disetujui! Status invoice: ' . $invoice->status);
    }
}
