<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Customer;
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
        $customer = Customer::where('user_id', Auth::id())->first();
        $pets = $customer ? Pet::where('customer_id', $customer->id)->paginate(10) : collect([]);
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
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'required|string|in:male,female,unknown',
            'color' => 'nullable|string|max:255',
        ]);

        // Ensure current user has a Customer record (create if missing)
        $customer = Customer::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'name' => Auth::user()->name ?? 'Owner',
                'email' => Auth::user()->email ?? null,
            ]
        );

        Pet::create([
            'name' => $request->name,
            'species' => $request->species,
            'breed' => $request->breed,
            'age' => $request->age,
            'weight' => $request->weight,
            'gender' => $request->gender,
            'color' => $request->color,
            'customer_id' => $customer->id,
        ]);

        return redirect()->route('pets.index')->with('success', 'Hewan berhasil ditambahkan.');
    }

    /**
     * Display the specified pet.
     */
    public function show(Pet $pet)
    {
        // Ensure the logged-in user owns the pet
        $ownerUserId = Customer::where('id', $pet->customer_id)->value('user_id');
        if ($ownerUserId !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pets.show', compact('pet'));
    }

    /**
     * Show the form for editing the specified pet.
     */
    public function edit(Pet $pet)
    {
        $ownerUserId = Customer::where('id', $pet->customer_id)->value('user_id');
        if ($ownerUserId !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('pets.edit', compact('pet'));
    }

    /**
     * Update the specified pet in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        $ownerUserId = Customer::where('id', $pet->customer_id)->value('user_id');
        if ($ownerUserId !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'required|string|in:male,female,unknown',
            'color' => 'nullable|string|max:255',
        ]);

        $pet->update($request->all());

        return redirect()->route('pets.index')->with('success', 'Hewan berhasil diperbarui.');
    }

    /**
     * Remove the specified pet from storage.
     */
    public function destroy(Pet $pet)
    {
        $ownerUserId = Customer::where('id', $pet->customer_id)->value('user_id');
        if ($ownerUserId !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pet->delete();
        return redirect()->route('pets.index')->with('success', 'Hewan berhasil dihapus.');
    }
}
