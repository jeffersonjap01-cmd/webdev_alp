<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email harus format yang valid.',
            'email.unique' => 'Email sudah digunakan.',
            'current_password.required_with' => 'Password saat ini wajib diisi jika ingin mengubah password.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Check current password if password is being changed
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
            }
        }

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
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
                $customer = $user->customer;
                $data['owner_info'] = [
                    'phone' => $customer->phone ?? null,
                    'address' => $customer->address ?? null,
                    'total_pets' => \App\Models\Pet::where('user_id', $user->id)->count(),
                    'total_appointments' => \App\Models\Appointment::where('user_id', $user->id)->count(),
                    'total_invoices' => \App\Models\Invoice::where('user_id', $user->id)->count(),
                    'registered_date' => $user->created_at->format('d F Y'),
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
                    'total_doctors' => \App\Models\Doctor::count(),
                    'total_customers' => \App\Models\Customer::count(),
                    'total_appointments' => \App\Models\Appointment::count(),
                ];
                break;
        }

        return $data;
    }
}
