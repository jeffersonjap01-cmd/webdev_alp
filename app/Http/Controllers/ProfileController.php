<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor;

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
     * Show the form for editing the authenticated user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        $profileData = $this->getProfileData($user);

        return view('profile.edit', compact('user', 'profileData'));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->fill(['name' => $validated['name'], 'email' => $validated['email']]);

        // Optional password update
        if ($request->filled('password')) {
            $request->validate(['password' => 'confirmed|min:8']);
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        // If user is doctor, update or create doctor profile fields
        if ($user->role === 'doctor') {
            $doctorData = $request->validate([
                'phone' => 'nullable|string|max:20',
                'specialization' => 'nullable|string|max:255',
                'bio' => 'nullable|string',
                'photo_url' => 'nullable|url',
            ]);

            if ($user->doctor) {
                // filter out nulls so we don't overwrite existing values with empty strings
                $user->doctor->update(array_filter($doctorData, function ($v) { return $v !== null && $v !== ''; }));
                $user->doctor->update(['name' => $user->name, 'email' => $user->email]);
            } else {
                $payload = array_merge(array_filter($doctorData, function ($v) { return $v !== null && $v !== ''; }), [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]);
                Doctor::create($payload);
            }
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
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
                        'total_prescriptions' => Schema::hasTable('prescriptions') ? $user->doctor->prescriptions()->count() : 0,
                    ];
                }
                break;

            case 'admin':
                $data['admin_info'] = [
                    'total_users' => \App\Models\User::count(),
                    'total_doctors' => \App\Models\Doctor::count(),
                    'total_customers' => \App\Models\User::where('role', 'customer')->count(),
                    'total_appointments' => \App\Models\Appointment::count(),
                ];
                break;
        }

        return $data;
    }
}
