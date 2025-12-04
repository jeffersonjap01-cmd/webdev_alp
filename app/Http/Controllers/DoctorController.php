<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Admin menambahkan dokter
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:6',
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string',
            'specialization'  => 'nullable|string',
            'service_duration'=> 'nullable|integer',
        ]);

        // Buat user baru dengan role doctor
        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'doctor',
        ]);

        // Buat profile dokter
        $doctor = Doctor::create([
            'user_id'          => $user->id,
            'name'             => $request->name,
            'phone'            => $request->phone,
            'email'            => $request->email,
            'specialization'   => $request->specialization,
            'service_duration' => $request->service_duration,
            'is_active'        => false,
        ]);

        return response()->json([
            'message' => 'Doctor successfully created by admin',
            'doctor'  => $doctor,
        ]);
    }

    /**
     * Dokter aktif/inaktif
     */
    public function toggleActive($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->is_active = !$doctor->is_active;
        $doctor->save();

        return response()->json([
            'message' => 'Doctor status updated',
            'is_active' => $doctor->is_active
        ]);
    }

    public function index()
    {
        return Doctor::with('user')->get();
    }

    public function show($id)
    {
        return Doctor::with('user')->findOrFail($id);
    }
}
