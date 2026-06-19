<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::apiResource('enderecos', AddressController::class)->parameters([
    'enderecos' => 'address',
]);

Route::apiResource('pacientes', PatientController::class)->parameters([
    'pacientes' => 'patient',
]);
