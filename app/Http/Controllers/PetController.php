<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Owner;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct()
    {
        // Auth middleware is applied at route level
    }

    /**
     * Display a listing of pets
     */
    public function index(Request $request)
    {
        $query = Pet::query()->with('owner');

        // Role-based filtering
        if (auth()->user()->role === 'owner' && auth()->user()->owner) {
            // Owner can only see their own pets
            $query->where('customer_id', auth()->user()->owner->id);
        } elseif (auth()->user()->role === 'vet') {
            // Vet can see all pets (for medical records)
            // No additional filtering needed
        } elseif (auth()->user()->role === 'admin') {
            // Admin can see all pets
            // No additional filtering needed
        }

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by species
        if ($request->has('species')) {
            $query->where('species', $request->species);
        }

        // Filter by owner (admin only)
        if ($request->has('customer_id') && auth()->user()->role === 'admin') {
            $query->where('customer_id', $request->customer_id);
        }

        $pets = $query->latest()->paginate(15);

        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new pet
     */
    public function create()
    {
        // Only owners can create pets for themselves
        if (auth()->user()->role !== 'owner' || !auth()->user()->owner) {
            abort(403, 'Hanya pemilik yang dapat menambahkan hewan peliharaan.');
        }

        return view('pets.create');
    }

    /**
     * Store a newly created pet
     */
    public function store(Request $request)
    {
        // Only owners can create pets for themselves
        if (auth()->user()->role !== 'owner' || !auth()->user()->owner) {
            abort(403, 'Hanya pemilik yang dapat menambahkan hewan peliharaan.');
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'species' => 'required|string',
            'breed'   => 'nullable|string|max:255',
            'age'     => 'nullable|numeric|min:0|max:50',
            'weight'  => 'nullable|numeric|min:0|max:200',
            'gender'  => 'nullable|string|in:male,female,unknown',
            'color'   => 'nullable|string|max:255',
        ]);

        $validated['customer_id'] = auth()->user()->owner->id;

        $pet = Pet::create($validated);

        return redirect()->route('pets.show', $pet)->with('success', 'Hewan peliharaan berhasil ditambahkan!');
    }

    /**
     * Display the specified pet
     */
    public function show(Pet $pet)
    {
        // Check ownership or admin/vet access
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id !== $pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat hewan peliharaan ini.');
            }
        }

        $pet->load(['owner', 'appointments.doctor', 'medicalRecords', 'vaccinations', 'prescriptions']);

        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified pet
     */
    public function edit(Pet $pet)
    {
        // Check ownership or admin access
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id !== $pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit hewan peliharaan ini.');
            }
        } elseif (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit hewan peliharaan.');
        }

        return view('pets.edit', compact('pet'));
    }

    /**
     * Update the specified pet
     */
    public function update(Request $request, Pet $pet)
    {
        // Check ownership or admin access
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id !== $pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk mengedit hewan peliharaan ini.');
            }
        } elseif (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit hewan peliharaan.');
        }

        $validated = $request->validate([
            'name'    => 'sometimes|string|max:255',
            'species' => 'sometimes|string',
            'breed'   => 'nullable|string|max:255',
            'age'     => 'nullable|numeric|min:0|max:50',
            'weight'  => 'nullable|numeric|min:0|max:200',
            'gender'  => 'nullable|string|in:male,female,unknown',
            'color'   => 'nullable|string|max:255',
        ]);

        $pet->update($validated);

        return redirect()->route('pets.show', $pet)->with('success', 'Data hewan peliharaan berhasil diperbarui!');
    }

    /**
     * Remove the specified pet
     */
    public function destroy(Pet $pet)
    {
        // Check ownership or admin access
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id !== $pet->customer_id) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus hewan peliharaan ini.');
            }
        } elseif (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus hewan peliharaan.');
        }

        // Check if pet has appointments or medical records
        if ($pet->appointments()->exists() || $pet->medicalRecords()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus hewan peliharaan yang memiliki rekam medis atau janji temu.');
        }

        $pet->delete();

        $message = auth()->user()->role === 'owner' ? 'Hewan peliharaan berhasil dihapus!' : 'Hewan peliharaan berhasil dihapus!';
        return redirect()->route('pets')->with('success', $message);
    }

    /**
     * Get all pets of a customer (for dropdown selections)
     */
    public function petsByCustomer($customerId)
    {
        // Check if user has access to this customer's pets
        if (auth()->user()->role === 'owner') {
            if (auth()->user()->owner->id != $customerId) {
                abort(403, 'Anda tidak memiliki akses untuk melihat hewan peliharaan ini.');
            }
        }

        $pets = Pet::where('customer_id', $customerId)->get();
        return response()->json($pets);
    }
}
