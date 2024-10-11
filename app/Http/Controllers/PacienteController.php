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

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $pacientes = $this->pacienteService->getAllPacientes($perPage);
    
            return response()->json($pacientes);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function detalhes($id)
    {
        try {
            $paciente = $this->pacienteService->findPacienteById($id);

            return response()->json([
                'id' => $paciente->id,
                'nome' => $paciente->nome,
                'hospital' => $paciente->hospital ? $paciente->hospital->nome : null,
                'plano_saude' => $paciente->planoSaude ? $paciente->planoSaude->nome : null,
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }


    public function buscarPorNome($nome)
    {
        try {
            $pacientes = $this->pacienteService->findPacientesByName($nome);
    
            return response()->json([
                'data' => $pacientes
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
}
