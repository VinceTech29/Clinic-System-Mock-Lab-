<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TransactionController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Patients
Route::resource('patients', PatientController::class);

// Doctors
Route::resource('doctors', DoctorController::class);

// Appointments
Route::resource('appointments', AppointmentController::class);
Route::patch('appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
Route::patch('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

// Transactions
Route::resource('transactions', TransactionController::class);
Route::patch('transactions/{transaction}/refund', [TransactionController::class, 'refund'])->name('transactions.refund');
Route::get('transactions-report', [TransactionController::class, 'report'])->name('transactions.report');
