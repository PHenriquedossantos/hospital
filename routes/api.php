<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AuthController;
use App\Services\ApiResponse;

Route::get('/status', function(){
    return ApiResponse::success('Api is running');
})->middleware('auth:sanctum');


Route::post('/importar', [ImportController::class, 'importar'])->middleware('auth:sanctum');
Route::get('/paciente/{id}', [PacienteController::class, 'show'])->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/verify-two-factor', [AuthController::class, 'verifyTwoFactor'])->middleware('auth:sanctum');
Route::post('/validate-token', [AuthController::class, 'validateToken']);
