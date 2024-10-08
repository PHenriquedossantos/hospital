<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue; 
use Illuminate\Queue\SerializesModels; 
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    protected $pacienteService;
    protected $hospitalService;
    protected $planoSaudeService;

    public function __construct($path, $pacienteService, $hospitalService, $planoSaudeService)
    {
        $this->path = $path;
        $this->pacienteService = $pacienteService;
        $this->hospitalService = $hospitalService;
        $this->planoSaudeService = $planoSaudeService;
    }

    public function handle()
    {
        try {
            $spreadsheet = IOFactory::load($this->path);
            $worksheet = $spreadsheet->getActiveSheet();
    
            $header = $worksheet->toArray()[0];
            \Log::info("Cabeçalhos XLS: ", $header);
    
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
    
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
    
                $rowAssociative = array_combine($header, $rowData);
                \Log::info("Row data: ", $rowAssociative);
    
                if (!isset($rowAssociative['Nome do Paciente'])) {
                    \Log::warning("Chave 'Nome do Paciente' não encontrada na linha: ", $rowAssociative);
                    continue;
                }
    
                $nomePaciente = trim($rowAssociative['Nome do Paciente']);
                $nomeHospital = trim($rowAssociative['Hospital']);
                $nomePlanoSaude = trim($rowAssociative['Plano de Saúde']);
    
                $hospital = $this->hospitalService->findOrCreate($nomeHospital);
                $planoSaude = $this->planoSaudeService->findOrCreate($nomePlanoSaude);
    
                $this->pacienteService->createOrUpdate($nomePaciente, $hospital->id, $planoSaude->id);
            }
        } catch (\Exception $e) {
            \Log::error("Erro ao processar o arquivo XLS: " . $e->getMessage());
        }
    }
}
