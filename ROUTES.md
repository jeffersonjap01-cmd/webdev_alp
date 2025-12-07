# API Routes - VetCare Laravel

## 📁 routes/api.php

```php
<?php

use App\Http\Controllers\{
    AuthController,
    OwnerController,
    PetController,
    DoctorController,
    MedicalRecordController,
    AppointmentController,
    InvoiceController,
    VaccinationController,
    PrescriptionController,
    DashboardController,
    ReportController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Owners (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('owners', OwnerController::class);
    });

    // Pets (Admin & Owner)
    Route::middleware('role:admin,owner')->group(function () {
        Route::apiResource('pets', PetController::class);
    });

    // Doctors (Admin only for CRUD, all can view)
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    Route::middleware('role:admin')->group(function () {
        Route::post('/doctors', [DoctorController::class, 'store']);
        Route::put('/doctors/{doctor}', [DoctorController::class, 'update']);
        Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);
        Route::patch('/doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus']);
    });

    // Medical Records (Admin & Vet)
    Route::middleware('role:admin,vet')->group(function () {
        Route::apiResource('medical-records', MedicalRecordController::class)->except(['update']);
        Route::get('/pets/{pet}/medical-records', [MedicalRecordController::class, 'byPet']);
    });

    // Appointments (Admin & Owner can create, All can view their own)
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);
    Route::middleware('role:admin,owner')->group(function () {
        Route::post('/appointments', [AppointmentController::class, 'store']);
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
    });
    Route::middleware('role:admin,vet')->group(function () {
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    });
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']);

    // Invoices & Payments
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
    Route::middleware('role:admin')->group(function () {
        Route::post('/invoices', [InvoiceController::class, 'store']);
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy']);
    });
    Route::middleware('role:admin,owner')->group(function () {
        Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay']);
    });

    // Vaccinations (All roles can view, Admin & Vet can manage)
    Route::get('/vaccinations', [VaccinationController::class, 'index']);
    Route::get('/vaccinations/{vaccination}', [VaccinationController::class, 'show']);
    Route::get('/vaccinations/upcoming', [VaccinationController::class, 'upcoming']);
    Route::middleware('role:admin,vet')->group(function () {
        Route::post('/vaccinations', [VaccinationController::class, 'store']);
        Route::put('/vaccinations/{vaccination}', [VaccinationController::class, 'update']);
        Route::patch('/vaccinations/{vaccination}/complete', [VaccinationController::class, 'complete']);
        Route::delete('/vaccinations/{vaccination}', [VaccinationController::class, 'destroy']);
    });

    // Prescriptions (Admin & Vet)
    Route::middleware('role:admin,vet')->group(function () {
        Route::apiResource('prescriptions', PrescriptionController::class);
        Route::patch('/prescriptions/{prescription}/status', [PrescriptionController::class, 'updateStatus']);
        Route::get('/pets/{pet}/prescriptions', [PrescriptionController::class, 'byPet']);
    });

    // Reports (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/reports/revenue', [ReportController::class, 'revenue']);
        Route::get('/reports/appointments', [ReportController::class, 'appointments']);
        Route::get('/reports/patients', [ReportController::class, 'patients']);
        Route::get('/reports/export/{type}', [ReportController::class, 'export']);
    });
});
```

---

## 🔒 Middleware untuk Role

```php
<?php
// app/Http/Middleware/CheckRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (!in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
```

### Register Middleware

```php
<?php
// bootstrap/app.php atau app/Http/Kernel.php (Laravel 10)

// Laravel 11 (bootstrap/app.php)
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})

// Laravel 10 (app/Http/Kernel.php)
protected $middlewareAliases = [
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

---

## 📋 API Endpoints Summary

### Authentication
```
POST   /api/register           - Register new user
POST   /api/login              - Login
POST   /api/logout             - Logout (Auth)
GET    /api/user               - Get current user (Auth)
```

### Dashboard
```
GET    /api/dashboard          - Get dashboard stats (Auth, role-based)
```

### Owners (Admin only)
```
GET    /api/owners             - List all owners
POST   /api/owners             - Create owner
GET    /api/owners/{id}        - Get owner details
PUT    /api/owners/{id}        - Update owner
DELETE /api/owners/{id}        - Delete owner
```

### Pets (Admin & Owner)
```
GET    /api/pets               - List pets (filtered by role)
POST   /api/pets               - Create pet
GET    /api/pets/{id}          - Get pet details
PUT    /api/pets/{id}          - Update pet
DELETE /api/pets/{id}          - Delete pet
```

### Doctors (Admin CRUD, All view)
```
GET    /api/doctors            - List doctors
POST   /api/doctors            - Create doctor (Admin)
GET    /api/doctors/{id}       - Get doctor details
PUT    /api/doctors/{id}       - Update doctor (Admin)
DELETE /api/doctors/{id}       - Delete doctor (Admin)
PATCH  /api/doctors/{id}/toggle-status - Toggle active/inactive (Admin)
```

### Medical Records (Admin & Vet)
```
GET    /api/medical-records    - List all records
POST   /api/medical-records    - Create record
GET    /api/medical-records/{id} - Get record details
GET    /api/pets/{id}/medical-records - Get pet's medical history
DELETE /api/medical-records/{id} - Delete record
```

### Appointments
```
GET    /api/appointments       - List appointments (role-filtered)
POST   /api/appointments       - Create appointment (Admin & Owner)
GET    /api/appointments/{id}  - Get appointment details
PUT    /api/appointments/{id}  - Update appointment (Admin & Owner)
PATCH  /api/appointments/{id}/status - Update status (Admin & Vet)
DELETE /api/appointments/{id}  - Delete appointment
```

### Invoices & Payments
```
GET    /api/invoices           - List invoices (role-filtered)
POST   /api/invoices           - Create invoice (Admin)
GET    /api/invoices/{id}      - Get invoice details
POST   /api/invoices/{id}/pay  - Process payment (Admin & Owner)
DELETE /api/invoices/{id}      - Delete invoice (Admin)
```

### Vaccinations
```
GET    /api/vaccinations       - List vaccinations
GET    /api/vaccinations/upcoming - Get upcoming vaccinations
POST   /api/vaccinations       - Create vaccination schedule (Admin & Vet)
GET    /api/vaccinations/{id}  - Get vaccination details
PUT    /api/vaccinations/{id}  - Update vaccination (Admin & Vet)
PATCH  /api/vaccinations/{id}/complete - Mark as completed (Admin & Vet)
DELETE /api/vaccinations/{id}  - Delete vaccination (Admin & Vet)
```

### Prescriptions (Admin & Vet)
```
GET    /api/prescriptions      - List prescriptions
POST   /api/prescriptions      - Create prescription
GET    /api/prescriptions/{id} - Get prescription details
PUT    /api/prescriptions/{id} - Update prescription
PATCH  /api/prescriptions/{id}/status - Update status
GET    /api/pets/{id}/prescriptions - Get pet's prescriptions
DELETE /api/prescriptions/{id} - Delete prescription
```

### Reports (Admin only)
```
GET    /api/reports/revenue    - Revenue reports
GET    /api/reports/appointments - Appointment statistics
GET    /api/reports/patients   - Patient statistics
GET    /api/reports/export/{type} - Export reports (PDF/Excel)
```

---

## 🔑 API Response Format

### Success Response
```json
{
  "data": {
    "id": 1,
    "name": "Max",
    "species": "Anjing"
  },
  "message": "Success"
}
```

### Paginated Response
```json
{
  "data": [...],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 100
  }
}
```

### Error Response
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

---

## 🧪 Testing API dengan Postman/Insomnia

### 1. Login
```
POST http://vetcare-backend.test/api/login
Content-Type: application/json

{
  "email": "admin@vetcare.com",
  "password": "password"
}
```

Response:
```json
{
  "user": {...},
  "token": "1|xxxxxxxxxxxxx"
}
```

### 2. Use Token untuk Request Berikutnya
```
GET http://vetcare-backend.test/api/dashboard
Authorization: Bearer 1|xxxxxxxxxxxxx
```

---

## 📊 Query Parameters

### Pagination
```
GET /api/pets?page=1&per_page=15
```

### Search
```
GET /api/owners?search=budi
```

### Filters
```
GET /api/appointments?status=scheduled&today=true
GET /api/doctors?active_only=true
GET /api/pets?species=Anjing
```

### Sorting
```
GET /api/invoices?sort=date&order=desc
```

### Include Relationships
```
GET /api/pets/1?include=owner,medical_records,vaccinations
```

---

## 🛡️ Rate Limiting

```php
// routes/api.php

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    // 60 requests per minute
});

Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    // For sensitive operations: 10 requests per minute
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay']);
});
```

---

## 🔐 API Authentication Flow

1. User registers/login → receives token
2. Store token in frontend (localStorage/cookie)
3. Include token in all API requests:
   ```
   Authorization: Bearer {token}
   ```
4. Token validates user & role
5. Middleware checks permissions
6. Controller processes request
7. Return JSON response

---

Lanjut ke **SEEDERS.md** untuk sample data! 🚀
