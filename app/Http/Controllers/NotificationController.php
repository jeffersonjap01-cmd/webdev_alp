<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send test WhatsApp notification
     */
    public function sendTestNotification(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $result = $this->whatsappService->sendNotification(
            $validated['phone'],
            $validated['message']
        );

        return response()->json($result);
    }

    /**
     * Send appointment reminder (triggered by JavaScript)
     */
    public function sendAppointmentReminder(Request $request, $appointmentId)
    {
        $appointment = Appointment::with(['user', 'pet', 'doctor'])->findOrFail($appointmentId);

        // Authorization check
        if (Auth::user()->role === 'customer' && $appointment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $result = $this->whatsappService->sendAppointmentReminder($appointment);

        return response()->json($result);
    }

    /**
     * Send appointment confirmation (triggered when doctor accepts)
     */
    public function sendAppointmentConfirmation(Request $request, $appointmentId)
    {
        // Only admin and doctors can send confirmation
        if (!in_array(Auth::user()->role, ['admin', 'doctor'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $appointment = Appointment::with(['user', 'pet', 'doctor'])->findOrFail($appointmentId);

        $result = $this->whatsappService->sendAppointmentConfirmation($appointment);

        return response()->json($result);
    }

    /**
     * Send prescription notification
     */
    public function sendPrescriptionNotification(Request $request, $prescriptionId)
    {
        // Only admin and doctors can send
        if (!in_array(Auth::user()->role, ['admin', 'doctor'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $prescription = Prescription::with(['pet.user', 'doctor', 'medications'])->findOrFail($prescriptionId);

        $result = $this->whatsappService->sendPrescriptionNotification($prescription);

        return response()->json($result);
    }

    /**
     * Get WhatsApp link (for manual sending)
     */
    public function getWhatsAppLink(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $link = $this->whatsappService->generateWhatsAppLink(
            $validated['phone'],
            $validated['message']
        );

        return response()->json([
            'success' => true,
            'link' => $link
        ]);
    }
}
