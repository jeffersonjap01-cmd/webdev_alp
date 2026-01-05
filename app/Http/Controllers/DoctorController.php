<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Display a listing of doctors (all roles can view)
     */
    public function index()
    {
        $doctors = Doctor::with('user')->latest()->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new doctor (Admin only)
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Store a newly created doctor (Admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:6|confirmed',
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string',
            'specialization'  => 'nullable|string|max:255',
            'service_duration'=> 'nullable|integer|min:15|max:120',
        ]);

        // Create user with doctor role
        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'name'     => $request->name,
            'role'     => 'doctor',
        ]);

        // Create doctor profile
        $doctor = Doctor::create([
            'user_id'          => $user->id,
            'name'             => $request->name,
            'phone'            => $request->phone,
            'email'            => $request->email,
            'specialization'   => $request->specialization,
            'service_duration' => $request->service_duration,
            'status'           => 'inactive',
        ]);

        return redirect()->route('doctors.create')->with('success', 'Dokter berhasil ditambahkan!');
        
    }

    /**
     * Display the specified doctor
     */
    public function show(Doctor $doctor)
    {
        $doctor->load('user');
        return view('doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified doctor (Admin only)
     */
    public function edit(Doctor $doctor)
    {
        $doctor->load('user');
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified doctor (Admin only)
     */
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name'            => 'sometimes|string|max:255',
            'email'           => 'sometimes|email|unique:users,email,' . $doctor->user->id,
            'phone'           => 'nullable|string',
            'specialization'  => 'nullable|string|max:255',
            'service_duration'=> 'nullable|integer|min:15|max:120',
        ]);

        // Update doctor profile
        $doctor->update($request->only(['name', 'phone', 'email', 'specialization', 'service_duration']));

        // Update user if name or email changed
        if (isset($request->name) || isset($request->email)) {
            $doctor->user->update($request->only(['name', 'email']));
        }

        return redirect()->route('doctors.show', $doctor)->with('success', 'Data dokter berhasil diperbarui!');
    }

    /**
     * Toggle doctor active/inactive status (Admin only)
     */
    public function toggleStatus(Doctor $doctor)
    {
        // Use the model helper to toggle the status field
        $doctor->toggleStatus();

        $status = $doctor->status === 'active' ? 'aktif' : 'nonaktif';
        return redirect()->route('doctors.show', $doctor)->with('success', "Status dokter berhasil diubah menjadi {$status}!");
    }

    /**
     * Remove the specified doctor (Admin only)
     */
    public function destroy(Doctor $doctor)
    {
        // Check if doctor has appointments
        if ($doctor->appointments()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus dokter yang masih memiliki janji temu.');
        }

        // Delete doctor profile and associated user
        $doctor->delete();
        $doctor->user->delete();

        return redirect()->route('doctors')->with('success', 'Dokter berhasil dihapus!');
    }
}
