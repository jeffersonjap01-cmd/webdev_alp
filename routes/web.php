<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('api')->group(function () {
    Route::post('/register/customer', [AuthController::class, 'registerCustomer']);
    Route::post('/register/admin', [AuthController::class, 'registerAdmin']);  // optional
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/', function () {
    return view('welcome');
});
