<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\NotificationController;


// =========================
// HOME (PUBLIC & DASHBOARD)
// =========================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect legacy dashboard route to home
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

// =========================
// APPOINTMENTS
// =========================
Route::middleware('auth')->group(function () {
    Route::middleware('role:doctor')->group(function () {
        Route::get('/doctor/examination/{appointment}', [ExaminationController::class, 'show'])->name('doctor.examination.show');
        Route::post('/doctor/examination/{appointment}', [ExaminationController::class, 'store'])->name('doctor.examination.store');
    });

    // All authenticated users can view list and details
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');

    // Customers (users) and admins can create appointments (must be before {appointment} route)
    Route::middleware('role:customer,admin')->group(function () {
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    // Show appointment details (must be after /appointments/create)
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

    // Admins can edit and delete appointments
    Route::middleware('role:admin')->group(function () {
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    // Status update (admin and vet)
    Route::middleware('role:admin,vet')->group(function () {
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    });

    // Doctor workflow actions
    Route::middleware('role:doctor')->group(function () {
        Route::post('/appointments/{appointment}/accept', [AppointmentController::class, 'accept'])->name('appointments.accept');
        Route::post('/appointments/{appointment}/decline', [AppointmentController::class, 'decline'])->name('appointments.decline');
        Route::post('/appointments/{appointment}/start', [AppointmentController::class, 'start'])->name('appointments.start');

    });

    // Customer can cancel pending appointments
    Route::middleware('role:customer')->group(function () {
        Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });
});


// =========================
// AUTH (GUEST)
// =========================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    // Use AuthController::webRegister for web registration form submissions
    Route::post('/register', [AuthController::class, 'webRegister'])->name('register.post');
});


// =========================
// AUTH (LOGGED IN)
// =========================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

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



// =========================
// MEDICAL RECORDS
// =========================
Route::middleware('auth')->group(function () {

    Route::get('/medical-records', [MedicalRecordController::class, 'index'])
        ->name('medical-records');

    // ✅ STATIC ROUTE HARUS DULUAN
    Route::middleware('role:admin,doctor')->group(function () {
        Route::get('/medical-records/create', [MedicalRecordController::class, 'create'])
            ->name('medical-records.create');

        Route::post('/medical-records', [MedicalRecordController::class, 'store'])
            ->name('medical-records.store');
    });

    // ✅ BARU DYNAMIC
    Route::get('/medical-records/{record}', [MedicalRecordController::class, 'show'])
        ->name('medical-records.show');

    // PDF Export route
    Route::get('/medical-records/{record}/export-pdf', [MedicalRecordController::class, 'exportPdf'])
        ->name('medical-records.export-pdf');

    Route::middleware('role:admin,doctor')->group(function () {
        Route::get('/medical-records/{record}/edit', [MedicalRecordController::class, 'edit'])
            ->name('medical-records.edit');

        Route::put('/medical-records/{record}', [MedicalRecordController::class, 'update'])
            ->name('medical-records.update');

        Route::delete('/medical-records/{record}', [MedicalRecordController::class, 'destroy'])
            ->name('medical-records.destroy');
    });
});



// =========================
// PRESCRIPTIONS
// =========================
Route::middleware('auth')->group(function () {
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');

    Route::middleware('role:admin,doctor')->group(function () {
        Route::get('/prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
        Route::post('/prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
        Route::get('/prescriptions/{prescription}/edit', [PrescriptionController::class, 'edit'])->name('prescriptions.edit');
        Route::put('/prescriptions/{prescription}', [PrescriptionController::class, 'update'])->name('prescriptions.update');
        Route::delete('/prescriptions/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');
    });

    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
});


// =========================
// MEDICATIONS
// =========================
Route::middleware('auth')->group(function () {
    Route::middleware('role:admin,doctor')->group(function () {
        Route::get('/medications', [MedicationController::class, 'index'])->name('medications');
        Route::get('/medications/create', [MedicationController::class, 'create'])->name('medications.create');
        Route::post('/medications', [MedicationController::class, 'store'])->name('medications.store');
        Route::get('/medications/{medication}', [MedicationController::class, 'show'])->name('medications.show');
        Route::get('/medications/{medication}/edit', [MedicationController::class, 'edit'])->name('medications.edit');
        Route::put('/medications/{medication}', [MedicationController::class, 'update'])->name('medications.update');
        Route::delete('/medications/{medication}', [MedicationController::class, 'destroy'])->name('medications.destroy');
    });
});


// =========================
// DIAGNOSES
// =========================
Route::middleware('auth')->group(function () {
    Route::middleware('role:admin,doctor')->group(function () {
        Route::get('/diagnoses', [DiagnosisController::class, 'index'])->name('diagnoses.index');
        Route::get('/diagnoses/create', [DiagnosisController::class, 'create'])->name('diagnoses.create');
        Route::post('/diagnoses', [DiagnosisController::class, 'store'])->name('diagnoses.store');
        Route::get('/diagnoses/{diagnosis}', [DiagnosisController::class, 'show'])->name('diagnoses.show');
        Route::get('/diagnoses/{diagnosis}/edit', [DiagnosisController::class, 'edit'])->name('diagnoses.edit');
        Route::put('/diagnoses/{diagnosis}', [DiagnosisController::class, 'update'])->name('diagnoses.update');
        Route::delete('/diagnoses/{diagnosis}', [DiagnosisController::class, 'destroy'])->name('diagnoses.destroy');
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
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');
    Route::get('/reports/patients', [ReportController::class, 'patients'])->name('reports.patients');
    Route::get('/reports/doctors', [ReportController::class, 'doctors'])->name('reports.doctors');
    Route::get('/reports/vaccinations', [ReportController::class, 'vaccinations'])->name('reports.vaccinations');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');
});

// =========================
// WHATSAPP NOTIFICATIONS (JavaScript-triggered)
// =========================
Route::middleware('auth')->prefix('api/notifications')->name('notifications.')->group(function () {
    // Send test notification
    Route::post('/send', [NotificationController::class, 'sendTestNotification'])->name('send');
    
    // Get WhatsApp link (no API needed)
    Route::post('/whatsapp-link', [NotificationController::class, 'getWhatsAppLink'])->name('whatsapp-link');
    
    // Appointment notifications
    Route::post('/appointment/{appointment}/reminder', [NotificationController::class, 'sendAppointmentReminder'])->name('appointment.reminder');
    Route::post('/appointment/{appointment}/confirmation', [NotificationController::class, 'sendAppointmentConfirmation'])->name('appointment.confirmation');
    
    // Prescription notifications
    Route::post('/prescription/{prescription}/notification', [NotificationController::class, 'sendPrescriptionNotification'])->name('prescription.notification');
});
