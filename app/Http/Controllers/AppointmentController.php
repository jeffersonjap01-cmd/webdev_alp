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
     * Get or create doctor record for authenticated user
     */
    private function getOrCreateDoctor(User $user)
    {
        if (!$user->doctor) {
            return \App\Models\Doctor::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'specialization' => 'General Practice',
                'status' => 'inactive', // Admin needs to activate
            ]);
        }
        return $user->doctor;
    }

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

        // Doctors can see all appointments (to see available appointments and their assigned ones)
        // They can then accept/decline appointments assigned to them
        // No filter needed for doctors - they see all appointments

        // Apply filters if provided
        if (request()->has('status') && request('status') !== '') {
            $query->where('status', request('status'));
        }
        
        if (request()->has('date') && request('date') !== '') {
            $query->whereDate('appointment_time', request('date'));
        }
        
        if (request()->has('doctor') && request('doctor') !== '' && $user->role === 'admin') {
            // Only admin can filter by doctor
            $query->where('doctor_id', request('doctor'));
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
            'pet_id' => 'required|exists:pets,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'service_type' => 'required|string',
            // 'duration' => 'required|integer|min:15|max:120',
            'notes' => 'nullable|string',
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
            'user_id' => auth()->id(),
            'pet_id' => $request->pet_id,
            'doctor_id' => $request->doctor_id,
            'appointment_time' => $appointmentDateTime,
            'service_type' => $request->service_type,
            // 'duration' => $request->duration,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('appointments')->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::with(['user', 'pet', 'doctor'])->findOrFail($id);

        // Authorization: Only appointment customer, admin, or assigned doctor can view
        $user = auth()->user();
        
        // Admin can view all appointments
        if ($user->role === 'admin') {
            return view('appointments.show', compact('appointment'));
        }
        
        // Customer can only view their own appointments
        if ($user->role === 'customer') {
            if ($appointment->user_id !== $user->id) {
                abort(403, 'Unauthorized to view this appointment.');
            }
            return view('appointments.show', compact('appointment'));
        }

        // Doctor can view appointments assigned to them
        if ($user->role === 'doctor') {
            $doctor = $this->getOrCreateDoctor($user);
            
            // Load the assigned doctor relationship if not already loaded
            if (!$appointment->relationLoaded('doctor')) {
                $appointment->load('doctor');
            }
            
            $assignedDoctor = $appointment->doctor;
            $canView = false;
            
            // Case 1: Appointment is assigned to this doctor (direct ID match)
            if ($appointment->doctor_id === $doctor->id) {
                $canView = true;
            }
            // Case 2: Appointment is assigned to a doctor with same user_id (recreated doctor record)
            elseif ($assignedDoctor && $assignedDoctor->user_id === $user->id) {
                // Auto-reassign appointment to the current doctor record if user_id matches
                $appointment->doctor_id = $doctor->id;
                $appointment->save();
                $canView = true;
            }
            // Case 3: Appointment is not assigned to any doctor yet (can be claimed)
            elseif (!$appointment->doctor_id) {
                $canView = true;
            }
            // Case 4: Appointment is assigned to another doctor but status is still pending
            // Doctor can view to potentially claim/take the appointment
            elseif ($appointment->doctor_id && in_array($appointment->status, ['pending'])) {
                // Allow viewing if status is still pending (doctor might want to take over)
                $canView = true;
            }
            
            if (!$canView) {
                $errorMsg = 'Unauthorized to view this appointment. This appointment is assigned to a different doctor and is not available.';
                if ($assignedDoctor) {
                    $errorMsg .= ' Assigned to: ' . $assignedDoctor->name . ' (ID: ' . $assignedDoctor->id . ').';
                }
                $errorMsg .= ' Status: ' . $appointment->status . '.';
                $errorMsg .= ' Please contact administrator if you need access.';
                abort(403, $errorMsg);
            }
            
            return view('appointments.show', compact('appointment'));
        }

        // If user role doesn't match any of the above, deny access
        abort(403, 'Unauthorized to view this appointment.');
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
            'pet_id' => 'required|exists:pets,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'service_type' => 'required|string',
            'duration' => 'required|integer|min:15|max:120',
            'notes' => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        if ($doctor->status !== 'active') {
            return back()->withErrors(['doctor_id' => 'Doctor is inactive. Appointment cannot be updated.'])->withInput();
        }

        $appointmentDateTime = $request->appointment_date . ' ' . $request->appointment_time;

        $appointment->update([
            'user_id' => auth()->id(),
            'pet_id' => $request->pet_id,
            'doctor_id' => $request->doctor_id,
            'appointment_time' => $appointmentDateTime,
            'service_type' => $request->service_type,
            'duration' => $request->duration,
            'notes' => $request->notes,
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

        // Only admin and doctor can update status
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'doctor'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $appointment = Appointment::findOrFail($id);

        // Doctor can only update their own appointments
        if ($user->role === 'doctor') {
            $doctor = $user->doctor;
            $assigned = $appointment->doctor;
            if (!($doctor && $assigned && $doctor->id === $assigned->id) && !($assigned && $assigned->user_id === $user->id)) {
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
        if ($user->role !== 'doctor') {
            abort(403, 'Unauthorized');
        }

        $appointment = Appointment::findOrFail($id);
        $doctor = $this->getOrCreateDoctor($user);
        
        // Load the assigned doctor relationship if not already loaded
        if (!$appointment->relationLoaded('doctor')) {
            $appointment->load('doctor');
        }
        
        $assignedDoctor = $appointment->doctor;
        $canAccept = false;
        
        // Case 1: Appointment is assigned to this doctor
        if ($appointment->doctor_id === $doctor->id) {
            $canAccept = true;
        }
        // Case 2: Appointment is assigned to a doctor with same user_id (recreated doctor record)
        elseif ($assignedDoctor && $assignedDoctor->user_id === $user->id) {
            // Auto-reassign to current doctor record
            $appointment->doctor_id = $doctor->id;
            $appointment->save();
            $canAccept = true;
        }
        // Case 3: Appointment is not assigned to any doctor yet (doctor can claim it)
        elseif (!$appointment->doctor_id) {
            // Assign to current doctor and accept
            $appointment->doctor_id = $doctor->id;
            $appointment->save();
            $canAccept = true;
        }
        // Case 4: Appointment is pending and assigned to another doctor (doctor can take over)
        elseif ($appointment->doctor_id && $appointment->status === 'pending') {
            // Reassign to current doctor and accept (taking over the appointment)
            $appointment->doctor_id = $doctor->id;
            $appointment->save();
            $canAccept = true;
        }

        if (!$canAccept) {
            abort(403, 'Unauthorized to accept this appointment. This appointment is already assigned to another doctor and is not in pending status.');
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
        if ($user->role !== 'doctor') {
            abort(403, 'Unauthorized');
        }

        $appointment = Appointment::findOrFail($id);
        $doctor = $this->getOrCreateDoctor($user);
        
        // Load the assigned doctor relationship if not already loaded
        if (!$appointment->relationLoaded('doctor')) {
            $appointment->load('doctor');
        }
        
        $assignedDoctor = $appointment->doctor;
        $canDecline = false;
        
        // Only allow decline if appointment is assigned to this doctor
        if ($appointment->doctor_id === $doctor->id) {
            $canDecline = true;
        }
        // Or if assigned to doctor with same user_id
        elseif ($assignedDoctor && $assignedDoctor->user_id === $user->id) {
            $canDecline = true;
        }

        if (!$canDecline) {
            abort(403, 'Unauthorized to decline this appointment. You can only decline appointments assigned to you.');
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
        if ($user->role !== 'doctor') {
            abort(403, 'Unauthorized');
        }

        $appointment = Appointment::findOrFail($id);
        $doctor = $this->getOrCreateDoctor($user);
        
        // Load the assigned doctor relationship if not already loaded
        if (!$appointment->relationLoaded('doctor')) {
            $appointment->load('doctor');
        }
        
        $assignedDoctor = $appointment->doctor;
        $canStart = false;
        
        // Only allow start if appointment is assigned to this doctor
        if ($appointment->doctor_id === $doctor->id) {
            $canStart = true;
        }
        // Or if assigned to doctor with same user_id
        elseif ($assignedDoctor && $assignedDoctor->user_id === $user->id) {
            $canStart = true;
        }

        if (!$canStart) {
            abort(403, 'Unauthorized to start this appointment. You can only start appointments assigned to you.');
        }

        if ($appointment->status !== 'accepted') {
            return redirect()->back()->withErrors(['error' => 'Only accepted appointments can be started.']);
        }

        $appointment->startProgress();

        // Redirect to appointment details so doctor can fill the in-progress forms
        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment started!');
    }

    /**
     * Doctor completes appointment
     */
    public function markCompleted($id)
    {
        $user = auth()->user();

        // Only doctors can complete appointments
        if ($user->role !== 'doctor') {
            abort(403, 'Unauthorized');
        }

        $appointment = Appointment::with(['pet', 'user'])->findOrFail($id);
        $doctor = $this->getOrCreateDoctor($user);
        
        // Load the assigned doctor relationship if not already loaded
        if (!$appointment->relationLoaded('doctor')) {
            $appointment->load('doctor');
        }
        
        $assignedDoctor = $appointment->doctor;
        $canComplete = false;
        
        // Only allow complete if appointment is assigned to this doctor
        if ($appointment->doctor_id === $doctor->id) {
            $canComplete = true;
        }
        // Or if assigned to doctor with same user_id
        elseif ($assignedDoctor && $assignedDoctor->user_id === $user->id) {
            $canComplete = true;
        }

        if (!$canComplete) {
            abort(403, 'Unauthorized to complete this appointment. You can only complete appointments assigned to you.');
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
