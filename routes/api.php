<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PacienteController;

Route::get('/user', function (Request $request) {
    return response()->json([
        'status' => true,
        'message' => "Listar usuários",
    ], 200);
});

Route::post('/importar', [ImportController::class, 'importar']);

Route::get('/paciente/{id}', [PacienteController::class, 'show']);
