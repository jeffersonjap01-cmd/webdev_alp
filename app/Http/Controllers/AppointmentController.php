<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Customer membuat appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'       => 'required|exists:customers,id',
            'pet_id'            => 'required|exists:pets,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'appointment_time'  => 'required|date',
            'notes'             => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        // Cek apakah dokter aktif
        if (!$doctor->is_active) {
            return response()->json([
                'message' => 'Doctor is inactive. Appointment cannot be created.',
            ], 400);
        }

        // Jika dokter aktif → booking lanjut
        $appointment = Appointment::create([
            'customer_id'      => $request->customer_id,
            'pet_id'           => $request->pet_id,
            'doctor_id'        => $request->doctor_id,
            'appointment_time' => $request->appointment_time,
            'status'           => 'approved',
            'notes'            => $request->notes,
        ]);

        return response()->json([
            'message' => 'Appointment created successfully',
            'appointment' => $appointment
        ], 201);
    }

    /**
     * Cancel appointment
     */
    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json(['message' => 'Appointment cancelled']);
    }

    /**
     * List all appointments
     */
    public function index()
    {
        return Appointment::with(['customer', 'pet', 'doctor'])->get();
    }

    /**
     * Detail appointment
     */
    public function show($id)
    {
        return Appointment::with(['customer', 'pet', 'doctor'])->findOrFail($id);
    }
}
