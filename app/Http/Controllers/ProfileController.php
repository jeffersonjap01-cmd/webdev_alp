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
            case 'owner':
                if ($user->owner) {
                    $data['owner_info'] = [
                        'phone' => $user->owner->phone,
                        'address' => $user->owner->address,
                        'registered_date' => $user->owner->registered_date->format('d F Y'),
                        'total_pets' => $user->owner->pets()->count(),
                        'total_appointments' => $user->owner->appointments()->count(),
                        'total_invoices' => $user->owner->invoices()->count(),
                    ];
                }
                break;

            case 'vet':
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
                    'total_users' => User::count(),
                    'total_doctors' => \App\Models\Doctor::count(),
                    'total_owners' => \App\Models\Customer::count(),
                    'total_appointments' => \App\Models\Appointment::count(),
                ];
                break;
        }

        return $data;
    }
}
