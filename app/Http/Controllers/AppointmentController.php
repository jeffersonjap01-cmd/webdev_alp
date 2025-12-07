<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Pet;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments
     */
    public function index()
    {
        $appointments = Appointment::with(['customer', 'pet', 'doctor'])
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $pets = Pet::with('owner')->get();
        $doctors = Doctor::where('is_active', true)->get();

        return view('appointments.create', compact('pets', 'doctors'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'pet_id'            => 'required|exists:pets,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'appointment_date'  => 'required|date|after_or_equal:today',
            'appointment_time'  => 'required',
            'service_type'      => 'required|string',
            'duration'          => 'required|integer|min:15|max:120',
            'notes'             => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        // Check if doctor is active
        if (!$doctor->is_active) {
            return back()->withErrors(['doctor_id' => 'Doctor is inactive. Appointment cannot be created.'])->withInput();
        }

        // Combine date and time
        $appointmentDateTime = $request->appointment_date . ' ' . $request->appointment_time;

        // If doctor active → proceed with booking
        $appointment = Appointment::create([
            'pet_id'           => $request->pet_id,
            'doctor_id'        => $request->doctor_id,
            'appointment_time' => $appointmentDateTime,
            'status'           => 'scheduled',
            'notes'            => $request->notes,
        ]);

        return redirect()->route('appointments')->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::with(['customer', 'pet', 'doctor'])->findOrFail($id);
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $pets = Pet::with('owner')->get();
        $doctors = Doctor::where('is_active', true)->get();

        return view('appointments.edit', compact('appointment', 'pets', 'doctors'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'pet_id'            => 'required|exists:pets,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'appointment_date'  => 'required|date',
            'appointment_time'  => 'required',
            'service_type'      => 'required|string',
            'duration'          => 'required|integer|min:15|max:120',
            'notes'             => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        if (!$doctor->is_active) {
            return back()->withErrors(['doctor_id' => 'Doctor is inactive. Appointment cannot be updated.'])->withInput();
        }

        $appointmentDateTime = $request->appointment_date . ' ' . $request->appointment_time;

        $appointment->update([
            'pet_id'           => $request->pet_id,
            'doctor_id'        => $request->doctor_id,
            'appointment_time' => $appointmentDateTime,
            'notes'            => $request->notes,
        ]);

        return redirect()->route('appointments')->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointments')->with('success', 'Appointment deleted successfully!');
    }

    /**
     * Update appointment status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled'
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->status = $request->status;
        $appointment->save();

        return response()->json(['message' => 'Appointment status updated successfully']);
    }

    /**
     * Cancel appointment
     */
    public function cancel($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = 'cancelled';
        $appointment->save();

        return redirect()->route('appointments')->with('success', 'Appointment cancelled successfully!');
    }
}
