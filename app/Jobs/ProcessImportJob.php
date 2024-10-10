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
    protected $batchSize = 1000; // Processa em lotes de 1000 linhas

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
            $reader = IOFactory::createReaderForFile($this->path);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($this->path);
            $worksheet = $spreadsheet->getActiveSheet();
    
            $header = $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1')[0]; // Captura o cabeçalho
            \Log::info("Cabeçalhos XLS: ", $header);
    
            // Processamento em lotes de 1000 linhas
            $rows = [];
            $currentRow = 1;
    
            foreach ($worksheet->getRowIterator(2) as $row) { // Inicia da segunda linha
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
    
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
    
                $rows[] = array_combine($header, $rowData);
    
                if ($currentRow % $this->batchSize == 0) {
                    // Processa o lote de 1000 linhas
                    $this->processBatch($rows);
                    $rows = []; // Limpa o array para o próximo lote
                }
    
                $currentRow++;
            }
    
            // Processa o último lote, caso tenha sobrado linhas
            if (count($rows) > 0) {
                $this->processBatch($rows);
            }
            \Log::info("Processamento concluído para o arquivo: {$this->path}");
        } catch (\Exception $e) {
            \Log::error("Erro ao processar o arquivo XLS: " . $e->getMessage());
        }
    }

    private function processBatch($rows)
    {
        foreach ($rows as $rowAssociative) {
            \Log::info("Processando linha: ", $rowAssociative);
    
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
}
