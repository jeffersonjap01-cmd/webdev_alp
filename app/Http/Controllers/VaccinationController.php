<?php

namespace App\Http\Controllers;

use App\Models\Vaccination;
use App\Models\Pet;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Display a listing of vaccinations
     */
    public function index(Request $request)
    {
        $query = Vaccination::query()->with(['pet.owner']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Upcoming vaccinations
        if ($request->boolean('upcoming')) {
            $query->upcoming(30);
        }

        // Overdue vaccinations
        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        // Filter by pet (for owner role)
        if (auth()->user()->role === 'owner' && auth()->user()->owner) {
            $query->whereHas('pet', function($q) {
                $q->where('customer_id', auth()->user()->owner->id);
            });
        }

        $vaccinations = $query->latest('next_date')->paginate(15);

        return view('vaccinations.index', compact('vaccinations'));
    }

    /**
     * Show the form for creating a new vaccination
     */
    public function create()
    {
        $pets = Pet::with('owner')->get();
        return view('vaccinations.create', compact('pets'));
    }

    /**
     * Store a newly created vaccination
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'vaccine_name' => 'required|string|max:255',
            'next_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $validated['last_date'] = now();
        $validated['status'] = 'scheduled';
        $validated['reminder_sent'] = false;

        $vaccination = Vaccination::create($validated);

        return redirect()->route('vaccinations.show', $vaccination)->with('success', 'Jadwal vaksinasi berhasil ditambahkan!');
    }

    /**
     * Display the specified vaccination
     */
    public function show(Vaccination $vaccination)
    {
        // Check access - owners can only see their pet's vaccinations
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id !== $vaccination->pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat jadwal vaksinasi ini.');
            }
        }

        return view('vaccinations.show', compact('vaccination'));
    }

    /**
     * Show the form for editing the specified vaccination
     */
    public function edit(Vaccination $vaccination)
    {
        // Check access - only admin and vet can edit
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit jadwal vaksinasi.');
        }

        $pets = Pet::with('owner')->get();
        return view('vaccinations.edit', compact('vaccination', 'pets'));
    }

    /**
     * Update the specified vaccination
     */
    public function update(Request $request, Vaccination $vaccination)
    {
        // Check access - only admin and vet can update
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit jadwal vaksinasi.');
        }

        $validated = $request->validate([
            'vaccine_name' => 'sometimes|string|max:255',
            'next_date' => 'sometimes|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $vaccination->update($validated);
        
        // Update status based on date
        $vaccination->updateStatus();

        return redirect()->route('vaccinations.show', $vaccination)->with('success', 'Jadwal vaksinasi berhasil diperbarui!');
    }

    /**
     * Mark vaccination as completed
     */
    public function complete(Request $request, Vaccination $vaccination)
    {
        // Check access - only admin and vet can complete
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk menandai vaccination sebagai selesai.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $vaccination->update([
            'last_date' => now(),
            'status' => 'completed',
            'notes' => $validated['notes'] ?? $vaccination->notes,
        ]);

        return redirect()->route('vaccinations.show', $vaccination)->with('success', 'Vaksinasi berhasil ditandai sebagai selesai!');
    }

    /**
     * Get upcoming vaccinations
     */
    public function upcoming(Request $request)
    {
        $days = $request->get('days', 30);
        $query = Vaccination::upcoming($days)->with(['pet.owner']);

        // Filter by pet ownership for owners
        if (auth()->user()->role === 'owner' && auth()->user()->owner) {
            $query->whereHas('pet', function($q) {
                $q->where('customer_id', auth()->user()->owner->id);
            });
        }

        $vaccinations = $query->latest('next_date')->get();

        return view('vaccinations.upcoming', compact('vaccinations'));
    }

    /**
     * Remove the specified vaccination
     */
    public function destroy(Vaccination $vaccination)
    {
        // Check access - only admin and vet can delete
        if (auth()->user()->role === 'owner') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus jadwal vaksinasi.');
        }

        $vaccination->delete();

        return redirect()->route('vaccinations')->with('success', 'Jadwal vaksinasi berhasil dihapus!');
    }
}