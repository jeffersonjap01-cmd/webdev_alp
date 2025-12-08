<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\DashboardController;

// Home route (guest accessible)
Route::get('/', function () {
    return view('home');
})->name('home');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'webRegister'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');
    Route::get('/user', [AuthController::class, 'me'])->name('user');
    
    // Dashboard (all authenticated users)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile (all authenticated users)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

// Customers routes (Admin for management, all for viewing)
Route::middleware('auth')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    
    Route::middleware('role:admin')->group(function () {
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });
    
    // Guest registration (creates customer)
    Route::post('/customers/register', [CustomerController::class, 'register'])->name('customers.register');
});

// Legacy owners routes (redirect to customers)
Route::redirect('/owners', '/customers');
Route::redirect('/owners/{id}', '/customers/{id}');

// Pets routes
Route::middleware('auth')->group(function () {
    // specific pet sub-routes first
    Route::get('/pets/{pet}/prescriptions', [PrescriptionController::class, 'byPet'])->name('pets.prescriptions');
    Route::get('/pets/{pet}/medical-records', [MedicalRecordController::class, 'byPet'])->name('pets.medical-records');

    // register standard CRUD routes for pets
    Route::resource('pets', PetController::class);
});

// Doctors routes (all can view, admin manages)
Route::middleware('auth')->group(function () {
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors');
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    
    Route::middleware('role:admin')->group(function () {
        Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
        Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
        Route::patch('/doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])->name('doctors.toggle-status');
    });
});

// Appointments routes
Route::middleware('auth')->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    
    Route::middleware('role:owner')->group(function () {
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });
    
    Route::middleware('role:admin')->group(function () {
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });
    
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
});

// Medical Records routes
Route::middleware('auth')->group(function () {
    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records');
    Route::get('/medical-records/{record}', [MedicalRecordController::class, 'show'])->name('medical-records.show');
    
    Route::middleware('role:admin,vet')->group(function () {
        Route::get('/medical-records/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
        Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::get('/medical-records/{record}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
        Route::put('/medical-records/{record}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
        Route::delete('/medical-records/{record}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
    });
});

// Prescriptions routes
Route::middleware('auth')->group(function () {
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
    
    Route::middleware('role:admin,vet')->group(function () {
        Route::get('/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('/prescriptions/{prescription}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
        Route::put('/prescriptions/{prescription}', [PrescriptionController::class, 'update'])->name('prescriptions.update');
        Route::patch('/prescriptions/{prescription}/status', [PrescriptionController::class, 'updateStatus'])->name('prescriptions.status');
        Route::delete('/prescriptions/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
    });
});

// Vaccinations routes
Route::middleware('auth')->group(function () {
    Route::get('/vaccinations', [VaccinationController::class, 'index'])->name('vaccinations');
    Route::get('/vaccinations/upcoming', [VaccinationController::class, 'upcoming'])->name('vaccinations.upcoming');
    Route::get('/vaccinations/{vaccination}', [VaccinationController::class, 'show'])->name('vaccinations.show');
    
    Route::middleware('role:admin,vet')->group(function () {
        Route::get('/vaccinations/create', [VaccinationController::class, 'create'])->name('vaccinations.create');
        Route::post('/vaccinations', [VaccinationController::class, 'store'])->name('vaccinations.store');
        Route::get('/vaccinations/{vaccination}/edit', [VaccinationController::class, 'edit'])->name('vaccinations.edit');
        Route::put('/vaccinations/{vaccination}', [VaccinationController::class, 'update'])->name('vaccinations.update');
        Route::patch('/vaccinations/{vaccination}/complete', [VaccinationController::class, 'complete'])->name('vaccinations.complete');
        Route::delete('/vaccinations/{vaccination}', [VaccinationController::class, 'destroy'])->name('vaccinations.destroy');
    });
});

// Payments routes
Route::middleware('auth')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    
    Route::middleware('role:admin')->group(function () {
        Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments/create', [PaymentController::class, 'createPayment'])->name('payments.store');
        Route::patch('/payments/{payment}/mark-paid', [PaymentController::class, 'markPaid'])->name('payments.mark-paid');
        Route::patch('/payments/{payment}/mark-rejected', [PaymentController::class, 'markRejected'])->name('payments.mark-rejected');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });
    
    Route::middleware('role:owner')->group(function () {
        Route::get('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
        Route::post('/payments/{payment}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    });
});

// Report routes (Admin only)
Route::middleware('auth')->middleware('role:admin')->group(function () {
    Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');
    Route::get('/reports/patients', [ReportController::class, 'patients'])->name('reports.patients');
    Route::get('/reports/doctors', [ReportController::class, 'doctors'])->name('reports.doctors');
    Route::get('/reports/vaccinations', [ReportController::class, 'vaccinations'])->name('reports.vaccinations');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
});

// API routes (for external API access)
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('api.customers');
    Route::post('/customers/register', [CustomerController::class, 'register'])->name('api.customers.register');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('api.customers.show');
    
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
});
