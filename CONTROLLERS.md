# Laravel Controllers - VetCare

## 📁 Controller Implementations

### 1. AuthController
```php
<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,vet,owner',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
```

---

### 2. OwnerController
```php
<?php
// app/Http/Controllers/OwnerController.php

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Owner::query()->with(['pets', 'user']);

        // Search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Pagination
        $owners = $query->latest()->paginate(15);

        return response()->json($owners);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:owners,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $validated['registered_date'] = now();

        $owner = Owner::create($validated);

        return response()->json($owner->load('pets'), 201);
    }

    public function show(Owner $owner)
    {
        return response()->json($owner->load(['pets', 'invoices', 'appointments']));
    }

    public function update(Request $request, Owner $owner)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:owners,email,' . $owner->id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
        ]);

        $owner->update($validated);

        return response()->json($owner->load('pets'));
    }

    public function destroy(Owner $owner)
    {
        $owner->delete();

        return response()->json([
            'message' => 'Owner deleted successfully'
        ]);
    }
}
```

---

### 3. PetController
```php
<?php
// app/Http/Controllers/PetController.php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $query = Pet::query()->with(['owner']);

        // Filter by owner (for owner role)
        if ($request->user()->isOwner() && $request->user()->owner) {
            $query->where('owner_id', $request->user()->owner->id);
        }

        // Search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by species
        if ($request->has('species')) {
            $query->bySpecies($request->species);
        }

        $pets = $query->latest()->paginate(15);

        return response()->json($pets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:100',
            'breed' => 'required|string|max:100',
            'age' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'color' => 'required|string|max:100',
            'gender' => 'required|in:Jantan,Betina',
            'photo_url' => 'nullable|url',
        ]);

        $pet = Pet::create($validated);

        return response()->json($pet->load('owner'), 201);
    }

    public function show(Pet $pet)
    {
        $this->authorize('view', $pet);

        return response()->json($pet->load([
            'owner',
            'medicalRecords.doctor',
            'appointments.doctor',
            'vaccinations',
            'prescriptions.medications'
        ]));
    }

    public function update(Request $request, Pet $pet)
    {
        $this->authorize('update', $pet);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'species' => 'sometimes|string|max:100',
            'breed' => 'sometimes|string|max:100',
            'age' => 'sometimes|numeric|min:0',
            'weight' => 'sometimes|numeric|min:0',
            'color' => 'sometimes|string|max:100',
            'gender' => 'sometimes|in:Jantan,Betina',
            'photo_url' => 'nullable|url',
        ]);

        $pet->update($validated);

        return response()->json($pet->load('owner'));
    }

    public function destroy(Pet $pet)
    {
        $this->authorize('delete', $pet);

        $pet->delete();

        return response()->json([
            'message' => 'Pet deleted successfully'
        ]);
    }
}
```

---

### 4. DoctorController
```php
<?php
// app/Http/Controllers/DoctorController.php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::query();

        // Filter active only
        if ($request->boolean('active_only')) {
            $query->active();
        }

        // Search
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filter by specialization
        if ($request->has('specialization')) {
            $query->bySpecialization($request->specialization);
        }

        $doctors = $query->latest()->paginate(15);

        return response()->json($doctors);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:100',
            'email' => 'required|email|unique:doctors,email',
            'phone' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
            'photo_url' => 'nullable|url',
            'bio' => 'nullable|string',
        ]);

        $doctor = Doctor::create($validated);

        return response()->json($doctor, 201);
    }

    public function show(Doctor $doctor)
    {
        return response()->json($doctor->load([
            'appointments' => fn($q) => $q->upcoming(),
            'medicalRecords' => fn($q) => $q->recent(30)
        ]));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'specialization' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'sometimes|string|max:20',
            'status' => 'sometimes|in:active,inactive',
            'photo_url' => 'nullable|url',
            'bio' => 'nullable|string',
        ]);

        $doctor->update($validated);

        return response()->json($doctor);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response()->json([
            'message' => 'Doctor deleted successfully'
        ]);
    }

    public function toggleStatus(Doctor $doctor)
    {
        $doctor->toggleStatus();

        return response()->json([
            'message' => 'Doctor status updated',
            'doctor' => $doctor
        ]);
    }
}
```

---

### 5. AppointmentController
```php
<?php
// app/Http/Controllers/AppointmentController.php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::query()->with(['pet.owner', 'doctor']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Today's appointments
        if ($request->boolean('today')) {
            $query->today();
        }

        // Upcoming only
        if ($request->boolean('upcoming')) {
            $query->upcoming();
        }

        // Filter by doctor (for vet role)
        if ($request->user()->isVet() && $request->user()->doctor) {
            $query->byDoctor($request->user()->doctor->id);
        }

        $appointments = $query->orderBy('date')->orderBy('time')->paginate(15);

        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment = Appointment::create($validated);

        return response()->json($appointment->load(['pet.owner', 'doctor']), 201);
    }

    public function show(Appointment $appointment)
    {
        return response()->json($appointment->load(['pet.owner', 'doctor']));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'date' => 'sometimes|date|after_or_equal:today',
            'time' => 'sometimes|date_format:H:i',
            'reason' => 'sometimes|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($validated);

        return response()->json($appointment->load(['pet.owner', 'doctor']));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        if ($validated['status'] === 'completed') {
            $appointment->complete();
        } elseif ($validated['status'] === 'cancelled') {
            $appointment->cancel();
        } else {
            $appointment->update(['status' => $validated['status']]);
        }

        return response()->json($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
```

---

### 6. InvoiceController
```php
<?php
// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query()->with(['owner', 'pet', 'items']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // This month
        if ($request->boolean('this_month')) {
            $query->thisMonth();
        }

        // Filter by owner (for owner role)
        if ($request->user()->isOwner() && $request->user()->owner) {
            $query->where('owner_id', $request->user()->owner->id);
        }

        $invoices = $query->latest('date')->paginate(15);

        return response()->json($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:owners,id',
            'pet_id' => 'required|exists:pets,id',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'owner_id' => $validated['owner_id'],
            'pet_id' => $validated['pet_id'],
            'date' => $validated['date'],
            'tax' => $validated['tax'] ?? 0,
            'discount' => $validated['discount'] ?? 0,
            'subtotal' => 0,
            'total' => 0,
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create($item);
        }

        $invoice->calculateTotal();

        return response()->json($invoice->load(['owner', 'pet', 'items']), 201);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return response()->json($invoice->load(['owner', 'pet', 'items']));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $this->authorize('pay', $invoice);

        $validated = $request->validate([
            'payment_method' => 'required|in:gopay,ovo,dana,credit_card,bank_transfer,cash',
            'payment_reference' => 'nullable|string',
        ]);

        $invoice->markAsPaid(
            $validated['payment_method'],
            $validated['payment_reference'] ?? null
        );

        return response()->json([
            'message' => 'Payment successful',
            'invoice' => $invoice->fresh()
        ]);
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);

        $invoice->delete();

        return response()->json([
            'message' => 'Invoice deleted successfully'
        ]);
    }
}
```

---

### 7. DashboardController
```php
<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\{Appointment, Doctor, Invoice, Owner, Pet, Vaccination};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $stats = match($user->role) {
            'admin' => $this->getAdminStats(),
            'vet' => $this->getVetStats($user),
            'owner' => $this->getOwnerStats($user),
        };

        return response()->json($stats);
    }

    private function getAdminStats()
    {
        return [
            'total_owners' => Owner::count(),
            'total_pets' => Pet::count(),
            'total_doctors' => Doctor::active()->count(),
            'today_appointments' => Appointment::today()->count(),
            'monthly_revenue' => Invoice::thisMonth()->paid()->sum('total'),
            'pending_payments' => Invoice::pending()->count(),
            'upcoming_vaccinations' => Vaccination::upcoming(30)->count(),
            'recent_activities' => $this->getRecentActivities(),
        ];
    }

    private function getVetStats($user)
    {
        $doctor = $user->doctor;
        
        return [
            'today_appointments' => Appointment::today()->byDoctor($doctor->id)->count(),
            'total_patients' => Pet::whereHas('appointments', function($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })->count(),
            'upcoming_appointments' => Appointment::upcoming()->byDoctor($doctor->id)->count(),
            'recent_medical_records' => $doctor->medicalRecords()->recent(30)->count(),
        ];
    }

    private function getOwnerStats($user)
    {
        $owner = $user->owner;
        
        return [
            'total_pets' => $owner->pets()->count(),
            'upcoming_appointments' => Appointment::upcoming()
                ->whereHas('pet', fn($q) => $q->where('owner_id', $owner->id))
                ->count(),
            'upcoming_vaccinations' => Vaccination::upcoming(30)
                ->whereHas('pet', fn($q) => $q->where('owner_id', $owner->id))
                ->count(),
            'pending_invoices' => Invoice::pending()->where('owner_id', $owner->id)->count(),
        ];
    }

    private function getRecentActivities()
    {
        $appointments = Appointment::with(['pet', 'doctor'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($apt) => [
                'type' => 'appointment',
                'message' => "Appointment dijadwalkan untuk {$apt->pet->name}",
                'time' => $apt->created_at,
            ]);

        $invoices = Invoice::with('owner')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($inv) => [
                'type' => 'payment',
                'message' => "Pembayaran {$inv->status} - {$inv->owner->name}",
                'time' => $inv->created_at,
            ]);

        return $appointments->merge($invoices)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }
}
```

---

## 🎯 Generate Controllers

```bash
# Generate dengan resource methods
php artisan make:controller OwnerController --resource --api
php artisan make:controller PetController --resource --api
php artisan make:controller DoctorController --resource --api

# Generate basic controller
php artisan make:controller AuthController
php artisan make:controller DashboardController
```

---

## 🔐 Form Request Validation

Untuk validation yang kompleks, gunakan Form Requests:

```bash
php artisan make:request StoreOwnerRequest
php artisan make:request StorePetRequest
php artisan make:request StoreAppointmentRequest
```

Example Form Request:
```php
<?php
// app/Http/Requests/StorePetRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:100',
            'breed' => 'required|string|max:100',
            'age' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'color' => 'required|string|max:100',
            'gender' => 'required|in:Jantan,Betina',
            'photo_url' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'owner_id.required' => 'Pemilik harus dipilih',
            'name.required' => 'Nama hewan harus diisi',
            // ... custom messages
        ];
    }
}
```

---

Lanjut ke **ROUTES.md** untuk API route definitions! 🚀
