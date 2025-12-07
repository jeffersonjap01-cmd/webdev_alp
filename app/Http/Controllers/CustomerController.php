<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Register a new owner (Guest)
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
            'name'     => $request->name,
            'role'     => 'owner',
        ]);

        // 2. Buat profile owner
        $owner = Owner::create([
            'user_id' => $user->id,
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'address' => $request->address,
            'registered_date' => now(),
            'status' => 'active',
        ]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Akun berhasil dibuat! Selamat datang di VetCare.');
    }

    /**
     * Get all owners (Admin only)
     */
    public function index(Request $request)
    {
        $query = Owner::query()->with(['user']);

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        $owners = $query->latest()->paginate(15);

        return view('owners.index', compact('owners'));
    }

    /**
     * Show a single owner
     */
    public function show(Owner $owner)
    {
        // Admin can see all owners, owner can only see own profile
        if (auth()->user()->role === 'owner' && auth()->user()->owner->id !== $owner->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat profil ini.');
        }

        return view('owners.show', compact('owner'));
    }

    /**
     * Show the form for creating a new owner (Admin only)
     */
    public function create()
    {
        return view('owners.create');
    }

    /**
     * Store a new owner (Admin only)
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

        // Create owner profile
        $owner = Owner::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'status' => 'active',
            'registered_date' => now(),
        ]);

        return redirect()->route('owners')->with('success', 'Pemilik berhasil ditambahkan!');
    }

    /**
     * Show the form for editing an owner (Admin only)
     */
    public function edit(Owner $owner)
    {
        return view('owners.edit', compact('owner'));
    }

    /**
     * Update owner
     */
    public function update(Request $request, Owner $owner)
    {
        // Admin can edit any owner, owner can only edit own profile
        if (auth()->user()->role === 'owner' && auth()->user()->owner->id !== $owner->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit profil ini.');
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:owners,email,' . $owner->id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
        ]);

        $owner->update($validated);

        // Update user name if changed
        if (isset($validated['name'])) {
            $owner->user->update(['name' => $validated['name']]);
        }

        $message = auth()->user()->role === 'owner' ? 'Profil berhasil diperbarui!' : 'Pemilik berhasil diperbarui!';
        return redirect()->route('owners.show', $owner)->with('success', $message);
    }

    /**
     * Delete owner (Admin only)
     */
    public function destroy(Owner $owner)
    {
        // Check if owner has appointments
        if ($owner->appointments()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus pemilik yang masih memiliki janji temu.');
        }

        $owner->delete();

        return redirect()->route('owners')->with('success', 'Pemilik berhasil dihapus!');
    }
}
