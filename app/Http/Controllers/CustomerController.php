<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Register customer (Guest)
     */
    public function register(Request $request)
    {
        $validated = $this->validateRegister($request);

        // Create user
        $user = User::create([
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'name'     => $validated['name'],
            'role'     => 'user',
        ]);

        // Create customer profile
        Customer::create([
            'user_id' => $user->id,
            'name'    => $validated['name'],
            'phone'   => $validated['phone'] ?? null,
            'email'   => $validated['email'],
            'address' => $validated['address'] ?? null,
        ]);

        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di VetCare.');
    }


    /**
     * List all customers (Admin)
     */
    public function index(Request $request)
    {
        $customers = Customer::with('user')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            })
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }


    /**
     * Show single customer
     */
    public function show(Customer $customer)
    {
        $this->authorizeCustomerAccess($customer);

        return view('customers.show', compact('customer'));
    }


    /**
     * Create customer (Admin)
     */
    public function create()
    {
        return view('customers.create');
    }


    /**
     * Store new customer (Admin)
     */
    public function store(Request $request)
    {
        $validated = $this->validateStore($request);

        $user = User::create([
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'name'     => $validated['name'],
            'role'     => 'user',
        ]);

        Customer::create([
            'user_id'  => $user->id,
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'address'  => $validated['address'],
        ]);

        return redirect()->route('customers')
            ->with('success', 'Customer berhasil ditambahkan!');
    }


    /**
     * Edit customer (Admin)
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }


    /**
     * Update customer
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorizeCustomerAccess($customer);

        $validated = $this->validateUpdate($request, $customer);

        $customer->update($validated);

        // Sync name to user table
        if (isset($validated['name'])) {
            $customer->user->update(['name' => $validated['name']]);
        }

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer berhasil diperbarui!');
    }


    /**
     * Delete customer (Admin)
     */
    public function destroy(Customer $customer)
    {
        if (method_exists($customer, 'appointments') && $customer->appointments()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus customer yang masih memiliki janji temu.');
        }

        $customer->delete();

        return redirect()->route('customers')
            ->with('success', 'Customer berhasil dihapus!');
    }



    /* ===========================
     |  HELPER METHODS
     =========================== */

    private function authorizeCustomerAccess(Customer $customer)
    {
        $user = Auth::user();

        if ($user->role !== 'customer') {
            return true;
        }

        if (!$user->customer) {
            abort(403, 'Profil customer tidak ditemukan.');
        }

        if ($user->customer->id !== $customer->id) {
            abort(403, 'Anda tidak memiliki akses untuk ini.');
        }
    }


    private function validateRegister(Request $request)
    {
        return $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string',
            'address'  => 'nullable|string',
        ]);
    }


    private function validateStore(Request $request)
    {
        return $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'address'  => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);
    }


    private function validateUpdate(Request $request, Customer $customer)
    {
        return $request->validate([
            'name'    => 'sometimes|string|max:255',
            'email'   => 'sometimes|email|unique:customers,email,' . $customer->id,
            'phone'   => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
        ]);
    }
}
