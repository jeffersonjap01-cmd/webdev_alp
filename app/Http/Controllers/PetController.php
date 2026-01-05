<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Always use paginate to ensure we have a paginator instance
        $pets = Pet::where('user_id', Auth::id())
                  ->orderBy('created_at', 'desc')
                  ->paginate(10);
                  
        return view('pets.index', compact('pets'));
    }

    /**
     * Show the form for creating a new pet
     */
    public function create()
    {
        return view('pets.create');
    }

    /**
     * Store a newly created pet in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'species_other' => 'required_if:species,Other|nullable|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'required|string|in:male,female,unknown',
            'color' => 'nullable|string|max:255',
        ]);

        // Use custom species if "Other" is selected
        $species = $request->species;
        if ($species === 'Other' && $request->filled('species_other')) {
            $species = $request->species_other;
        }

        Pet::create([
            'name' => $request->name,
            'species' => $species,
            'breed' => $request->breed,
            'age' => $request->age,
            'weight' => $request->weight,
            'gender' => $request->gender,
            'color' => $request->color,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('pets.index')->with('success', 'Hewan berhasil ditambahkan.');
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet)
    {
        // Ensure the logged-in user owns the pet
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pets.edit', compact('pet'));
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'species_other' => 'required_if:species,Other|nullable|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'required|string|in:male,female,unknown',
            'color' => 'nullable|string|max:255',
        ]);

        // Use custom species if "Other" is selected
        $updateData = $request->all();
        if ($request->species === 'Other' && $request->filled('species_other')) {
            $updateData['species'] = $request->species_other;
        }
        unset($updateData['species_other']); // Remove species_other from update data

        $pet->update($updateData);

        return redirect()->route('pets.index')->with('success', 'Hewan berhasil diperbarui.');
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet)
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pet->delete();
        return redirect()->route('pets.index')->with('success', 'Hewan berhasil dihapus.');
    }
}
