<?php

namespace App\Http\Controllers;

use App\Models\{Appointment, Doctor, Invoice, User, Pet, Vaccination};
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $query = Invoice::query();

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } elseif ($request->has('month')) {
            $query->whereMonth('date', Carbon::parse($request->month)->month)
                  ->whereYear('date', Carbon::parse($request->month)->year);
        } else {
            // Default to current month
            $query->thisMonth();
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'paid');
        }

        $revenue = $query->sum('total');
        $invoices = $query->with(['customer', 'pet'])->get();

        return response()->json([
            'total_revenue' => $revenue,
            'invoice_count' => $invoices->count(),
            'average_invoice' => $invoices->count() > 0 ? $revenue / $invoices->count() : 0,
            'period' => $request->month ?? 'current_month',
            'breakdown' => $invoices->groupBy('status')->map->sum('total'),
            'invoices' => $invoices
        ]);
    }

    public function appointments(Request $request)
    {
        $query = Appointment::query()->with(['pet.customer', 'doctor']);

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } elseif ($request->has('month')) {
            $query->whereMonth('date', Carbon::parse($request->month)->month)
                  ->whereYear('date', Carbon::parse($request->month)->year);
        } else {
            // Default to current month
            $query->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year);
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->get();

        return response()->json([
            'total_appointments' => $appointments->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'daily_breakdown' => $appointments->groupBy('date')->map->count(),
            'doctor_breakdown' => $appointments->groupBy('doctor_id')->map->count(),
            'status_breakdown' => $appointments->groupBy('status')->map->count(),
            'appointments' => $appointments
        ]);
    }

    public function patients(Request $request)
    {
        $query = Pet::query()->with(['customer', 'appointments']);

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereHas('appointments', function($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            });
        } elseif ($request->has('month')) {
            $query->whereHas('appointments', function($q) use ($request) {
                $q->whereMonth('date', Carbon::parse($request->month)->month)
                  ->whereYear('date', Carbon::parse($request->month)->year);
            });
        }

        $pets = $query->get();

        $speciesBreakdown = $pets->groupBy('species')->map->count();
        $ageBreakdown = $pets->groupBy('age')->map->count();

        return response()->json([
            'total_pets' => $pets->count(),
            'new_pets_this_month' => $pets->where('created_at', '>=', now()->startOfMonth())->count(),
            'species_breakdown' => $speciesBreakdown,
            'age_breakdown' => $ageBreakdown,
            'pets_with_appointments' => $pets->filter(function($pet) {
                return $pet->appointments->isNotEmpty();
            })->count(),
            'active_pets' => $pets->count(),
            'pets' => $pets
        ]);
    }

    public function doctors(Request $request)
    {
        $query = Doctor::query()->with(['appointments']);

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereHas('appointments', function($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            });
        } elseif ($request->has('month')) {
            $query->whereHas('appointments', function($q) use ($request) {
                $q->whereMonth('date', Carbon::parse($request->month)->month)
                  ->whereYear('date', Carbon::parse($request->month)->year);
            });
        }

        $doctors = $query->get();

        return response()->json([
            'total_doctors' => $doctors->count(),
            'active_doctors' => $doctors->where('status', 'active')->count(),
            'specialization_breakdown' => $doctors->groupBy('specialization')->map->count(),
            'appointment_workload' => $doctors->map(function($doctor) {
                return [
                    'doctor' => $doctor,
                    'appointment_count' => $doctor->appointments->count()
                ];
            }),
            'doctors' => $doctors
        ]);
    }

    public function vaccinations(Request $request)
    {
        $query = Vaccination::query()->with(['pet']);

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('next_date', [$request->start_date, $request->end_date]);
        } elseif ($request->has('month')) {
            $query->whereMonth('next_date', Carbon::parse($request->month)->month)
                  ->whereYear('next_date', Carbon::parse($request->month)->year);
        }

        $vaccinations = $query->get();

        return response()->json([
            'total_scheduled' => $vaccinations->where('status', 'scheduled')->count(),
            'completed' => $vaccinations->where('status', 'completed')->count(),
            'overdue' => $vaccinations->where('status', 'overdue')->count(),
            'upcoming_this_week' => Vaccination::upcoming(7)->count(),
            'upcoming_this_month' => Vaccination::upcoming(30)->count(),
            'vaccine_breakdown' => $vaccinations->groupBy('vaccine_name')->map->count(),
            'vaccinations' => $vaccinations
        ]);
    }

    public function dashboard(Request $request)
    {
        $currentMonth = now()->startOfMonth();

        return response()->json([
            'overview' => [
                'total_revenue' => Invoice::thisMonth()->paid()->sum('total'),
                'total_appointments' => Appointment::whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)->count(),
                'total_pets' => Pet::count(),
                'total_doctors' => Doctor::active()->count(),
                'pending_invoices' => Invoice::pending()->count(),
            ],
            'monthly_trends' => [
                'revenue' => $this->getMonthlyRevenue(),
                'appointments' => $this->getMonthlyAppointments(),
            ],
            'recent_activities' => [
                'latest_appointments' => Appointment::with(['pet.customer', 'doctor'])
                    ->latest()->take(5)->get(),
                'recent_payments' => Invoice::with('customer')
                    ->where('status', 'paid')->latest('paid_at')->take(5)->get(),
                'upcoming_vaccinations' => Vaccination::upcoming(7)
                    ->with('pet')->take(5)->get(),
            ]
        ]);
    }

    private function getMonthlyRevenue()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = Invoice::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->where('status', 'paid')
                ->sum('total');
            
            $data[] = [
                'month' => $month->format('Y-m'),
                'revenue' => $revenue
            ];
        }
        return $data;
    }

    private function getMonthlyAppointments()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Appointment::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->count();
            
            $data[] = [
                'month' => $month->format('Y-m'),
                'appointments' => $count
            ];
        }
        return $data;
    }

    public function export(Request $request, $type)
    {
        // This would typically generate PDF/Excel files
        // For now, return JSON data that can be exported
        
        return match($type) {
            'revenue' => $this->revenue($request),
            'appointments' => $this->appointments($request),
            'patients' => $this->patients($request),
            'doctors' => $this->doctors($request),
            'vaccinations' => $this->vaccinations($request),
            default => response()->json(['message' => 'Invalid export type'], 400)
        };
    }
}