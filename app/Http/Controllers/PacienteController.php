<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Services\PacienteService;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    protected $pacienteService;

    public function __construct(PacienteService $pacienteService)
    {
        $this->pacienteService = $pacienteService;
    }

    public function show($id)
    {
        try {
            $paciente = $this->pacienteService->findPacienteById($id);

            return response()->json([
                'nome' => $paciente->nome,
                'hospital' => $paciente->hospital->nome,
                'plano_saude' => $paciente->planoSaude->nome,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }
}
