<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Register a new customer
     */
    public function register(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string',
        ]);

        // 1. Buat user (login)
        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        // 2. Buat profile customer
        $customer = Customer::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        return response()->json([
            'message'  => 'Customer registered successfully',
            'user'     => $user,
            'customer' => $customer,
        ], 201);
    }

    /**
     * Get all customers
     */
    public function index()
    {
        return Customer::with('user')->get();
    }

    /**
     * Show a single customer
     */
    public function show($id)
    {
        return Customer::with('user')->findOrFail($id);
    }
}
