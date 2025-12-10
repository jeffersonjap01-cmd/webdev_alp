<?php

namespace App\Http\Controllers;

use App\Models\Vaccination;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VaccinationController extends Controller
{
    // Middleware sudah di-route, jadi constructor kosong

    /**
     * Display a listing of vaccinations
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Vaccination::with(['pet.customer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Upcoming vaccinations (30 days default)
        if ($request->boolean('upcoming')) {
            $query->upcoming(30);
        }

        // Overdue vaccinations
        if ($request->boolean('overdue')) {
            $query->overdue();
        }

        // Customer sees only their pets
        if ($user->role === 'customer' && $user->customer) {
            $query->whereHas('pet', fn($q) =>
                $q->where('customer_id', $user->customer->id)
            );
        }

        $vaccinations = $query->latest('next_date')->paginate(15);

        return view('vaccinations.index', compact('vaccinations'));
    }

    /**
     * Display form to create vaccination
     */
    public function create()
    {
        return view('vaccinations.create', [
            'pets' => Pet::with('customer')->get()
        ]);
    }

    /**
     * Store new vaccination
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id'       => 'required|exists:pets,id',
            'vaccine_name' => 'required|string|max:255',
            'next_date'    => 'required|date|after_or_equal:today',
            'notes'        => 'nullable|string',
        ]);

        $validated['last_date'] = now();
        $validated['status'] = 'scheduled';
        $validated['reminder_sent'] = false;

        $vaccination = Vaccination::create($validated);

        return redirect()
            ->route('vaccinations.show', $vaccination)
            ->with('success', 'Jadwal vaksinasi berhasil ditambahkan!');
    }

    /**
     * Display one vaccination
     */
    public function show(Vaccination $vaccination)
    {
        $user = Auth::user();

        // Customers can only see their pet’s vaccinations
        if ($user->role === 'customer') {
            if ($vaccination->pet->customer_id !== $user->customer->id) {
                abort(403, 'Anda tidak memiliki akses ke jadwal vaksinasi ini.');
            }
        }

        return view('vaccinations.show', compact('vaccination'));
    }

    /**
     * Edit form
     */
    public function edit(Vaccination $vaccination)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit vaksinasi.');
        }

        return view('vaccinations.edit', [
            'vaccination' => $vaccination,
            'pets' => Pet::with('customer')->get(),
        ]);
    }

    /**
     * Update vaccination
     */
    public function update(Request $request, Vaccination $vaccination)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit vaksinasi.');
        }

        $validated = $request->validate([
            'vaccine_name' => 'sometimes|string|max:255',
            'next_date'    => 'sometimes|date|after_or_equal:today',
            'notes'        => 'nullable|string',
        ]);

        $vaccination->update($validated);

        // Update status automatically
        $vaccination->updateStatus();

        return redirect()
            ->route('vaccinations.show', $vaccination)
            ->with('success', 'Jadwal vaksinasi berhasil diperbarui!');
    }

    /**
     * Mark vaccination as completed
     */
    public function complete(Request $request, Vaccination $vaccination)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk menandai vaksinasi.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $vaccination->update([
            'last_date' => now(),
            'status'    => 'completed',
            'notes'     => $validated['notes'] ?? $vaccination->notes,
        ]);

        return redirect()
            ->route('vaccinations.show', $vaccination)
            ->with('success', 'Vaksinasi berhasil ditandai selesai!');
    }

    /**
     * List upcoming vaccinations
     */
    public function upcoming(Request $request)
    {
        $user = Auth::user();
        $days = $request->get('days', 30);

        $query = Vaccination::upcoming($days)->with('pet.customer');

        if ($user->role === 'customer') {
            $query->whereHas('pet', fn($q) =>
                $q->where('customer_id', $user->customer->id)
            );
        }

        $vaccinations = $query->latest('next_date')->get();

        return view('vaccinations.upcoming', compact('vaccinations'));
    }

    /**
     * Delete vaccination
     */
    public function destroy(Vaccination $vaccination)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus vaksinasi.');
        }

        $vaccination->delete();

        return redirect()
            ->route('vaccinations')
            ->with('success', 'Jadwal vaksinasi berhasil dihapus!');
    }
}
