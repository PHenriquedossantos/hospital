<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportRequest;
use App\Jobs\ProcessImportJob;
use App\Services\PacienteService;
use App\Services\HospitalService;
use App\Services\PlanoSaudeService;

class ImportController extends Controller
{
    protected $pacienteService;
    protected $hospitalService;
    protected $planoSaudeService;

    public function __construct(
        PacienteService $pacienteService,
        HospitalService $hospitalService,
        PlanoSaudeService $planoSaudeService
    ) {
        $this->pacienteService = $pacienteService;
        $this->hospitalService = $hospitalService;
        $this->planoSaudeService = $planoSaudeService;
    }

    public function importar(ImportRequest $request)
    {
        
        $path = $request->file('file')->store('uploads', 'local');
    
        $absolutePath = storage_path('app/' . $path);

        if (!file_exists($absolutePath)) {
            return response()->json(['message' => 'Arquivo não encontrado.'], 404);
        }

        ProcessImportJob::dispatch($absolutePath, $this->pacienteService, $this->hospitalService, $this->planoSaudeService);

        return response()->json([
            'message' => 'Arquivo enviado com sucesso e processamento iniciado.',
        ]);
    }
}
