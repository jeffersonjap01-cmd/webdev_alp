<?php

namespace App\Http\Controllers;

use App\Models\{Appointment, Doctor, Invoice, User, Pet, Vaccination, Payment};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Auth middleware is applied at route level
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        $stats = match($user->role) {
            'admin' => $this->getAdminStats(),
            'doctor' => $this->getVetStats($user),
            'customer' => $this->getCustomerStats($user),
            default => $this->getDefaultStats(),
        };

        return view('dashboard.index', compact('stats', 'user'));
    }

    private function getAdminStats()
    {
        return [
            'total_users' => \App\Models\User::where('role', 'customer')->count(),
            'total_pets' => Pet::count(),
            'total_doctors' => Doctor::active()->count(),
            'today_appointments' => Appointment::whereDate('appointment_time', today())->count(),
            'monthly_revenue' => Payment::whereMonth('created_at', now()->month)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'pending_payments' => Payment::where('status', 'unpaid')->count(),
            'upcoming_vaccinations' => Vaccination::where('next_date', '>=', now())
                ->where('next_date', '<=', now()->addDays(30))
                ->count(),
            'recent_activities' => $this->getRecentActivities(),
        ];
    }

    private function getVetStats($user)
    {
        // Find the doctor record for this user
        $doctor = $user->doctor ?? Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            return $this->getDefaultStats();
        }
        
        return [
            'today_appointments' => Appointment::whereDate('appointment_time', today())
                ->where('doctor_id', $doctor->id)
                ->count(),
            'total_patients' => Pet::whereHas('appointments', function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })->count(),
            'upcoming_appointments' => Appointment::where('appointment_time', '>=', now())
                ->where('doctor_id', $doctor->id)
                ->count(),
            'recent_medical_records' => \App\Models\MedicalRecord::where('doctor_id', $doctor->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
        ];
    }

    private function getCustomerStats($user)
    {
        return [
            'total_pets' => Pet::where('user_id', $user->id)->count(),
            'upcoming_appointments' => Appointment::where('appointment_time', '>=', now())
                ->where('user_id', $user->id)
                ->count(),
            'upcoming_vaccinations' => Vaccination::where('next_date', '>=', now())
                ->where('next_date', '<=', now()->addDays(30))
                ->whereHas('pet', fn($q) => $q->where('user_id', $user->id))
                ->count(),
            'pending_payments' => Payment::where('status', 'unpaid')
                ->whereHas('medicalRecord.pet', fn($q) => $q->where('user_id', $user->id))
                ->count(),
        ];
    }

    private function getDefaultStats()
    {
        return [
            'total_users' => 0,
            'total_pets' => 0,
            'total_doctors' => 0,
            'today_appointments' => 0,
            'monthly_revenue' => 0,
            'pending_payments' => 0,
            'upcoming_vaccinations' => 0,
            'recent_activities' => [],
        ];
    }

    private function getRecentActivities()
    {
        $appointments = Appointment::with(['pet', 'doctor'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($apt) => [
                'type' => 'appointment',
                'message' => "Janji temu dijadwalkan untuk {$apt->pet->name} dengan Dr. {$apt->doctor->name}",
                'time' => $apt->created_at,
                'icon' => 'calendar-check',
                'color' => 'blue',
            ]);

        $payments = Payment::with(['medicalRecord.pet.user'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($payment) => [
                'type' => 'payment',
                'message' => "Pembayaran {$payment->status} - Rp " . number_format($payment->total_amount),
                'time' => $payment->created_at,
                'icon' => 'credit-card',
                'color' => $payment->status === 'paid' ? 'green' : 'yellow',
            ]);

        return $appointments->merge($payments)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }
}