<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments
     */
    public function index()
    {
        $user = auth()->user();
        $query = Appointment::with(['user', 'pet', 'doctor'])->orderBy('appointment_time', 'desc');

        // Users see only their appointments
        if ($user->role === 'customer') {
            $query->where('user_id', $user->id);
        }

        // Vets see only appointments assigned to them
        if ($user->role === 'dokter') {
            $doctor = $user->doctor;
            if ($doctor) {
                $query->where('doctor_id', $doctor->id);
            }
        }

        $appointments = $query->paginate(10);
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $user = auth()->user();
        
        // If customer, only show their pets
        if ($user->role === 'customer') {
            $pets = Pet::where('user_id', $user->id)->with('user')->get();
        } else {
            $pets = Pet::with('user')->get();
        }

        $doctors = Doctor::active()->get();

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
        if ($doctor->status !== 'active') {
            return back()->withErrors(['doctor_id' => 'Doctor is inactive. Appointment cannot be created.'])->withInput();
        }

        // Combine date and time
        $appointmentDateTime = $request->appointment_date . ' ' . $request->appointment_time;

        // If doctor active → proceed with booking
        $appointment = Appointment::create([
            'user_id'          => auth()->id(),
            'pet_id'           => $request->pet_id,
            'doctor_id'        => $request->doctor_id,
            'appointment_time' => $appointmentDateTime,
            'service_type'     => $request->service_type,
            'duration'         => $request->duration,
            'status'           => 'pending',
            'notes'            => $request->notes,
        ]);

        return redirect()->route('appointments')->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::with(['user', 'pet', 'doctor'])->findOrFail($id);
        
        // Authorization: Only appointment owner, admin, or assigned doctor can view
        $user = auth()->user();
        if ($user->role === 'customer' && $appointment->user_id !== $user->id) {
            abort(403, 'Unauthorized to view this appointment.');
        }
        
        if ($user->role === 'dokter') {
            $doctor = $user->doctor;
            if ($doctor && $appointment->doctor_id !== $doctor->id) {
                abort(403, 'Unauthorized to view this appointment.');
            }
        }
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        // Only admin can edit appointments
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can edit appointments.');
        }
        
        $pets = Pet::with('user')->get();
        $doctors = Doctor::active()->get();

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

        if ($doctor->status !== 'active') {
            return back()->withErrors(['doctor_id' => 'Doctor is inactive. Appointment cannot be updated.'])->withInput();
        }

        $appointmentDateTime = $request->appointment_date . ' ' . $request->appointment_time;

        $appointment->update([
            'user_id'          => auth()->id(),
            'pet_id'           => $request->pet_id,
            'doctor_id'        => $request->doctor_id,
            'appointment_time' => $appointmentDateTime,
            'service_type'     => $request->service_type,
            'duration'         => $request->duration,
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
        
        // Only admin can delete appointments
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only administrators can delete appointments.');
        }
        
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

        // Only admin and dokter can update status
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'dokter'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $appointment = Appointment::findOrFail($id);
        
        // Dokter can only update their own appointments
        if ($user->role === 'dokter') {
            $doctor = $user->doctor;
            if ($doctor && $appointment->doctor_id !== $doctor->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }
        
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

    /**
     * Doctor accepts appointment
     */
    public function accept($id)
    {
        $user = auth()->user();
        
        // Only doctors can accept appointments
        if ($user->role !== 'dokter') {
            abort(403, 'Unauthorized');
        }
        
        $appointment = Appointment::findOrFail($id);
        $doctor = $user->doctor;
        
        // Doctor can only accept their own appointments
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized to accept this appointment');
        }
        
        $appointment->accept();
        
        return redirect()->back()->with('success', 'Appointment accepted successfully!');
    }

    /**
     * Doctor declines appointment
     */
    public function decline($id)
    {
        $user = auth()->user();
        
        // Only doctors can decline appointments
        if ($user->role !== 'dokter') {
            abort(403, 'Unauthorized');
        }
        
        $appointment = Appointment::findOrFail($id);
        $doctor = $user->doctor;
        
        // Doctor can only decline their own appointments
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized to decline this appointment');
        }
        
        $appointment->decline();
        
        return redirect()->back()->with('info', 'Appointment declined.');
    }

    /**
     * Doctor starts appointment (mark as in progress)
     */
    public function start($id)
    {
        $user = auth()->user();
        
        // Only doctors can start appointments
        if ($user->role !== 'dokter') {
            abort(403, 'Unauthorized');
        }
        
        $appointment = Appointment::findOrFail($id);
        $doctor = $user->doctor;
        
        // Doctor can only start their own accepted appointments
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized to start this appointment');
        }
        
        if ($appointment->status !== 'accepted') {
            return redirect()->back()->withErrors(['error' => 'Only accepted appointments can be started.']);
        }
        
        $appointment->startProgress();
        
        return redirect()->back()->with('success', 'Appointment started!');
    }

    /**
     * Doctor completes appointment
     */
    public function markCompleted($id)
    {
        $user = auth()->user();
        
        // Only doctors can complete appointments
        if ($user->role !== 'dokter') {
            abort(403, 'Unauthorized');
        }
        
        $appointment = Appointment::with(['pet', 'user'])->findOrFail($id);
        $doctor = $user->doctor;
        
        // Doctor can only complete their own in-progress appointments
        if (!$doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403, 'Unauthorized to complete this appointment');
        }
        
        if (!in_array($appointment->status, ['accepted', 'in_progress'])) {
            return redirect()->back()->withErrors(['error' => 'Only accepted or in-progress appointments can be completed.']);
        }
        
        $appointment->complete();
        
        // Redirect based on service type
        if ($appointment->service_type === 'Vaccination') {
            return redirect()->route('vaccinations.create', ['appointment_id' => $appointment->id])
                ->with('success', 'Appointment completed! Please record the vaccination details.');
        } else {
            return redirect()->route('medical-records.create', ['appointment_id' => $appointment->id])
                ->with('success', 'Appointment completed! Please create the medical record.');
        }
    }
}
