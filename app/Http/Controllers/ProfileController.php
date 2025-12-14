<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Load related data based on user role
        $profileData = $this->getProfileData($user);
        
        return view('profile.index', compact('user', 'profileData'));
    }

    /**
     * Get profile data based on user role.
     */
    private function getProfileData(User $user)
    {
        $data = [
            'basic_info' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'member_since' => $user->created_at->format('d F Y'),
            ]
        ];

        switch ($user->role) {
            case 'customer':
                $data['customer_info'] = [
                    'total_pets' => \App\Models\Pet::where('user_id', $user->id)->count(),
                    'total_appointments' => \App\Models\Appointment::where('user_id', $user->id)->count(),
                    'total_invoices' => \App\Models\Invoice::where('user_id', $user->id)->count(),
                ];
                break;

            case 'doctor':
                if ($user->doctor) {
                    $data['doctor_info'] = [
                        'phone' => $user->doctor->phone,
                        'specialization' => $user->doctor->specialization,
                        'status' => $user->doctor->status,
                        'bio' => $user->doctor->bio,
                        'photo_url' => $user->doctor->photo_url,
                        'total_appointments' => $user->doctor->appointments()->count(),
                        'total_medical_records' => $user->doctor->medicalRecords()->count(),
                        'total_prescriptions' => $user->doctor->prescriptions()->count(),
                    ];
                }
                break;

            case 'admin':
                $data['admin_info'] = [
                    'total_users' => \App\Models\User::where('role', 'customer')->count(),
                    'total_doctors' => \App\Models\Doctor::count(),
                    'total_appointments' => \App\Models\Appointment::count(),
                ];
                break;
        }

        return $data;
    }
}
