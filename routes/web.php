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

// =========================
// HOME (PUBLIC)
// =========================
Route::get('/', fn() => view('home'))->name('home');


// =========================
// AUTH (GUEST)
// =========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [CustomerController::class, 'register'])->name('customers.register');
});


// =========================
// AUTH (LOGGED IN)
// =========================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});


// =========================
// CUSTOMERS
// =========================
Route::middleware('auth')->group(function () {

    // Semua user bisa lihat list customer
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');

    // Hanya admin bisa CRUD customer
    Route::middleware('role:admin')->group(function () {
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    });
});


// =========================
// PETS
// =========================
Route::middleware('auth')->group(function () {
    Route::resource('pets', PetController::class);
    Route::get('/pets/{pet}/prescriptions', [PrescriptionController::class, 'byPet'])->name('pets.prescriptions');
    Route::get('/pets/{pet}/medical-records', [MedicalRecordController::class, 'byPet'])->name('pets.medical-records');
});


// =========================
// DOCTORS
// =========================
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


// =========================
// APPOINTMENTS
// =========================
Route::middleware('auth')->group(function () {

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

    // Customer bikin appointment
    Route::middleware('role:customer')->group(function () {
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    // Admin edit appointment
    Route::middleware('role:admin')->group(function () {
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
});


// =========================
// MEDICAL RECORDS
// =========================
Route::middleware('auth')->group(function () {
    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records');
    Route::get('/medical-records/{record}', [MedicalRecordController::class, 'show'])->name('medical-records.show');

    Route::middleware('role:admin,doctor')->group(function () {
        Route::get('/medical-records/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
        Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::get('/medical-records/{record}/edit', [MedicalRecordController::class, 'edit'])->name('medical-records.edit');
        Route::put('/medical-records/{record}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
        Route::delete('/medical-records/{record}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
    });
});


// =========================
// PAYMENT
// =========================
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

    Route::middleware('role:customer')->group(function () {
        Route::get('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
        Route::post('/payments/{payment}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    });
});


// =========================
// REPORTS
// =========================
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');
    Route::get('/reports/patients', [ReportController::class, 'patients'])->name('reports.patients');
    Route::get('/reports/doctors', [ReportController::class, 'doctors'])->name('reports.doctors');
    Route::get('/reports/vaccinations', [ReportController::class, 'vaccinations'])->name('reports.vaccinations');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
});

