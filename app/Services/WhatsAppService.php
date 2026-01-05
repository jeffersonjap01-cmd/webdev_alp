<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp notification using Fonnte API
     * You can also use: Wablas, Twilio, or other services
     */
    public function sendNotification($phone, $message)
    {
        // Option 1: Using Fonnte (Indonesian service - easy and cheap)
        // Sign up at https://fonnte.com
        $fonnte_token = env('FONNTE_TOKEN', '');

        // Normalize phone early and validate basic length/format
        $formattedPhone = $this->formatPhoneNumber($phone);
        // basic E.164 length check (without '+')
        $digitsOnly = preg_replace('/[^0-9]/', '', $formattedPhone);
        $len = strlen($digitsOnly);
        if ($len < 8 || $len > 15) {
            Log::warning('Invalid phone format for WhatsApp link', ['phone' => $phone, 'formatted' => $formattedPhone]);
            return [
                'success' => false,
                'message' => 'Invalid phone number format for WhatsApp',
                'formatted_phone' => $formattedPhone,
            ];
        }
        
        if (!$fonnte_token) {
            Log::info('FONNTE_TOKEN not configured, using WhatsApp Web link instead');
            // Fall back to WhatsApp Web link (use already formatted phone)
            $link = $this->generateWhatsAppLink($formattedPhone, $message);
            return [
                'success' => true,
                'message' => 'WhatsApp link generated',
                'whatsapp_link' => $link,
                'formatted_phone' => $formattedPhone,
            ];
        }

        try {
            $response = Http::withHeaders([
                // Use Bearer scheme which is commonly required by API providers
                'Authorization' => "Bearer {$fonnte_token}",

                'Accept' => 'application/json',
            ])->post('https://api.fonnte.com/send', [
                'target' => $formattedPhone,
                'message' => $message,
                'countryCode' => '62', // Indonesia country code
            ]);

            $body = $response->json();

            // Inspect response body for known success indicators
            $isSuccessful = false;
            if ($response->successful()) {
                if ((isset($body['success']) && $body['success'] === true)
                    || (isset($body['status']) && in_array(strtolower($body['status']), ['success', 'ok', 'sent']))
                    || isset($body['data'])
                ) {
                    $isSuccessful = true;
                }
            }

            if ($isSuccessful) {
                return ['success' => true, 'message' => 'Notification sent successfully'];
            }

            // Log the full response for debugging when send fails
            Log::error('Fonnte send failed', ['status' => $response->status(), 'body' => $body]);

            // Fallback to generating a WhatsApp Web link so UI can still open/send manually
            $link = $this->generateWhatsAppLink($formattedPhone, $message);
            Log::warning('Fonnte API failed, falling back to WhatsApp link', ['link' => $link]);

            return [
                'success' => true,
                'message' => 'Fonnte API failed — using WhatsApp link fallback',
                'whatsapp_link' => $link,
                'response' => $body,
                'formatted_phone' => $formattedPhone,
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp notification error: ' . $e->getMessage());

            // On exception, fallback to wa.me link so the user can still send manually
            $link = $this->generateWhatsAppLink($formattedPhone, $message);
            return [
                'success' => true,
                'message' => 'Error sending via API — using WhatsApp link fallback',
                'whatsapp_link' => $link,
                'error' => $e->getMessage(),
                'formatted_phone' => $formattedPhone,
            ];
        }
    }

    /**
     * Alternative: Generate WhatsApp Web link (no API needed)
     */
    public function generateWhatsAppLink($phone, $message)
    {
        $formattedPhone = $this->formatPhoneNumber($phone);

        // Ensure message is valid UTF-8 and remove replacement characters
        $messageUtf8 = mb_convert_encoding($message, 'UTF-8', 'UTF-8');
        $messageUtf8 = preg_replace('/\x{FFFD}/u', '', $messageUtf8);

        // Use rawurlencode for RFC 3986 compliant encoding
        $encodedMessage = rawurlencode($messageUtf8);

        return "https://wa.me/{$formattedPhone}?text={$encodedMessage}";
    }

    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If starts with 0, replace with 62 (Indonesia)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // If doesn't start with country code, add 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Send appointment reminder
     */
    public function sendAppointmentReminder($appointment)
    {
        // Get phone from customer relationship
        $customer = $appointment->pet->user->customer ?? null;
        $customerPhone = $customer->phone ?? null;
        
        if (!$customerPhone) {
            return ['success' => false, 'message' => 'Customer phone not found'];
        }

        $message = "*VetCare Appointment Reminder*\n\n";
        $message .= "Halo {$appointment->user->name},\n\n";
        $message .= "Pengingat untuk appointment:\n";
        $message .= "Pet: {$appointment->pet->name}\n";
        $message .= "Date: " . \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, H:i') . "\n";
        $message .= "Doctor: {$appointment->doctor->name}\n";
        $message .= "VetCare Clinic\n\n";
        $message .= "Terima kasih! ";

        return $this->sendNotification($customerPhone, $message);
    }

    /**
     * Send appointment confirmation
     */
    public function sendAppointmentConfirmation($appointment)
    {
        // Get phone from customer relationship
        $customer = $appointment->pet->user->customer ?? null;
        $customerPhone = $customer->phone ?? null;
        
        if (!$customerPhone) {
            return ['success' => false, 'message' => 'Customer phone not found'];
        }

        $message = "*Appointment Confirmed - VetCare*\n\n";
        $message .= "Halo {$appointment->user->name},\n\n";
        $message .= "Appointment Anda telah dikonfirmasi!\n\n";
        $message .= "Details:\n";
        $message .= "Pet: {$appointment->pet->name}\n";
        $message .= "Date: " . \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, H:i') . "\n";
        $message .= "Doctor: {$appointment->doctor->name}\n";
        $message .= "Duration: {$appointment->duration} minutes\n\n";
        $message .= "Mohon datang 10 menit lebih awal.\n";
        $message .= "Terima kasih! 🐾";

        return $this->sendNotification($customerPhone, $message);
    }

    /**
     * Send prescription ready notification
     */
    public function sendPrescriptionNotification($prescription)
    {
        // Get phone from customer relationship
        $customer = $prescription->pet->user->customer ?? null;
        $customerPhone = $customer->phone ?? null;
        
        if (!$customerPhone) {
            return ['success' => false, 'message' => 'Customer phone not found'];
        }

        $message = "💊 *Prescription Ready - VetCare*\n\n";
        $message .= "Halo {$prescription->pet->user->name},\n\n";
        $message .= "Resep obat untuk {$prescription->pet->name} sudah siap!\n\n";
        $message .= "📋 Prescription Details:\n";
        $message .= "👨‍⚕️ Doctor: {$prescription->doctor->name}\n";
        $message .= "📅 Date: " . $prescription->created_at->format('d M Y') . "\n\n";
        
        if ($prescription->medications->count() > 0) {
            $message .= "💊 Medications:\n";
            foreach ($prescription->medications as $index => $med) {
                $message .= ($index + 1) . ". {$med->medicine_name}\n";
                $message .= "   Dosage: {$med->dosage}\n";
                $message .= "   Frequency: {$med->frequency}\n";
                $message .= "   Duration: {$med->duration}\n\n";
            }
        }
        
        $message .= "Silakan ambil obat di klinik kami.\n";
        $message .= "Terima kasih! 🐾";

        return $this->sendNotification($customerPhone, $message);
    }
}
