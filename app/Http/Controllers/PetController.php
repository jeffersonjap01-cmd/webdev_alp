<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Customer;
use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Add pet for a customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name'        => 'required|string|max:255',
            'species'     => 'required|string',
            'breed'       => 'nullable|string',
            'age'         => 'nullable|integer',
            'gender'      => 'nullable|string',
            'color'       => 'nullable|string',
        ]);

        $pet = Pet::create($request->all());

        return response()->json([
            'message' => 'Pet added',
            'pet'     => $pet
        ], 201);
    }

    /**
     * Get all pets of a customer
     */
    public function petsByCustomer($customerId)
    {
        $pets = Pet::where('customer_id', $customerId)->get();
        return response()->json($pets);
    }

    /**
     * Get single pet detail
     */
    public function show($id)
    {
        return Pet::findOrFail($id);
    }
}
