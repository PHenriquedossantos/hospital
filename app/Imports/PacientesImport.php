<?php

// app/Imports/PacientesImport.php

namespace App\Imports;

use App\Models\Paciente;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PacientesImport implements ToCollection, WithHeadingRow
{
    protected $pacienteService;
    protected $hospitalService;
    protected $planoSaudeService;

    public function __construct($pacienteService, $hospitalService, $planoSaudeService)
    {
        $this->pacienteService = $pacienteService;
        $this->hospitalService = $hospitalService;
        $this->planoSaudeService = $planoSaudeService;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Verificar e criar hospital
            $hospital = $this->hospitalService->findOrCreate($row['hospital']);

            // Verificar e criar plano de saúde
            $planoSaude = $this->planoSaudeService->findOrCreate($row['plano_de_saude']);

            // Criar ou atualizar paciente
            $this->pacienteService->createOrUpdate($row['nome_do_paciente'], $hospital->id, $planoSaude->id);
        }
    }
}
