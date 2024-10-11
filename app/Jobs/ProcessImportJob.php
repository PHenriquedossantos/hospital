<?php

namespace App\Jobs;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue; 
use Illuminate\Queue\SerializesModels; 

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
            ini_set('memory_limit', '500M');

            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($this->path);

            foreach ($reader->getSheetIterator() as $sheet) {
                $header = null;
                
                foreach ($sheet->getRowIterator() as $row) {
                    $rowData = $row->getCells();
                    
                    if (!$header) {
                        $header = array_map('trim', $rowData);
                        \Log::info("Cabeçalhos XLS: ", $header);
                        continue;
                    }

                    $rowAssociative = array_combine($header, array_map('trim', $rowData));
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
            }

            $reader->close();
        } catch (\Exception $e) {
            \Log::error("Erro ao processar o arquivo XLS: " . $e->getMessage());
        }
    }
}
