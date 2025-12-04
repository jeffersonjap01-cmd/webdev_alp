<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;

Route::prefix('api/payments')->group(function () {
    Route::post('/create', [PaymentController::class, 'createPayment']); // admin
    Route::post('/{id}/upload-proof', [PaymentController::class, 'uploadProof']); // customer upload bukti
    Route::patch('/{id}/mark-paid', [PaymentController::class, 'markPaid']); // admin confirm
    Route::get('/{id}', [PaymentController::class, 'show']);
});

Route::prefix('api/medical-records')->group(function () {
    Route::post('/', [MedicalRecordController::class, 'store']);
    Route::get('/{id}', [MedicalRecordController::class, 'show']);
});

Route::prefix('api/appointments')->group(function () {
    Route::post('/', [AppointmentController::class, 'store']);
    Route::get('/', [AppointmentController::class, 'index']);
    Route::get('/{id}', [AppointmentController::class, 'show']);
    Route::patch('/{id}/cancel', [AppointmentController::class, 'cancel']);
});

Route::prefix('api/pets')->group(function () {
    Route::post('/', [PetController::class, 'store']); // tambah pet
    Route::get('/customer/{id}', [PetController::class, 'petsByCustomer']); // ambil semua pet customer
    Route::get('/{id}', [PetController::class, 'show']); // detail pet
});

Route::prefix('api/doctors')->group(function () {
    Route::post('/', [DoctorController::class, 'store']); 
    Route::get('/', [DoctorController::class, 'index']);
    Route::get('/{id}', [DoctorController::class, 'show']);
    Route::patch('/{id}/toggle-active', [DoctorController::class, 'toggleActive']);
});

Route::prefix('api')->group(function () {
    Route::post('/customers/register', [CustomerController::class, 'register']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
});

Route::get('/', function () {
    return view('welcome');
});
