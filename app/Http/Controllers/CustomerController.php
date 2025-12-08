<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Register a new customer (Guest)
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

        // 1. Create user (login)
        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'name'     => $request->name,
            'role'     => 'owner',
        ]);

        // 2. Create profile customer
        $customer = Customer::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
        ]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di VetCare.');
    }

    /**
     * Get all customers (Admin only)
     */
    public function index(Request $request)
    {
        $query = Customer::query()->with(['user']);

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        $customers = $query->latest()->paginate(15);

        return view('owners.index', compact('customers'));
    }

    /**
     * Show a single customer
     */
    public function show(Customer $customer)
    {
        // Admin can see all customers, owner can only see own profile
        if (auth()->user()->role === 'owner' && auth()->user()->customer->id !== $customer->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil ini.');
        }

        return view('owners.show', compact('customer'));
    }

    /**
     * Show the form for creating a new customer (Admin only)
     */
    public function create()
    {
        return view('owners.create');
    }

    /**
     * Store a new customer (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create user
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'name' => $validated['name'],
            'role' => 'owner',
        ]);

        // Create customer profile
        $customer = Customer::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect()->route('owners')->with('success', 'Customer berhasil ditambahkan!');
    }

    /**
     * Show the form for editing a customer (Admin only)
     */
    public function edit(Customer $customer)
    {
        return view('owners.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, Customer $customer)
    {
        // Admin can edit any customer, owner can only edit own profile
        if (auth()->user()->role === 'owner' && auth()->user()->customer->id !== $customer->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit profil ini.');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
        ]);

        $customer->update($validated);

        // Update user name if changed
        if (isset($validated['name'])) {
            $customer->user->update(['name' => $validated['name']]);
        }

        $message = auth()->user()->role === 'owner' ? 'Profil berhasil diperbarui!' : 'Customer berhasil diperbarui!';
        return redirect()->route('owners.show', $customer)->with('success', $message);
    }

    /**
     * Delete customer (Admin only)
     */
    public function destroy(Customer $customer)
    {
        // Check if customer has appointments
        if ($customer->appointments()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus customer yang masih memiliki janji temu.');
        }

        $customer->delete();

        return redirect()->route('owners')->with('success', 'Customer berhasil dihapus!');
    }
}
