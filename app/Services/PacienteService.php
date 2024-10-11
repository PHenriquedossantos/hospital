<?php

namespace App\Services;

use App\Models\Paciente;

class PacienteService
{
    protected $pacienteModel;

    public function __construct(Paciente $pacienteModel)
    {
        $this->pacienteModel = $pacienteModel;
    }

    /**
     * Cria ou atualiza um paciente.
     *
     * @param string $nome Nome do paciente.
     * @param int $hospitalId ID do hospital associado ao paciente.
     * @param int $planoSaudeId ID do plano de saúde associado ao paciente.
     * @return Paciente O paciente criado ou atualizado.
     */
    public function createOrUpdate($nome, $hospitalId, $planoSaudeId)
    {
        return $this->pacienteModel::updateOrCreate(
            ['nome' => $nome],
            ['hospital_id' => $hospitalId, 'plano_saude_id' => $planoSaudeId]
        );
    }

    /**
     * Encontra um paciente pelo ID.
     *
     * @param int $id ID do paciente.
     * @return Paciente
     * @throws Exception Se o paciente não for encontrado ou ocorrer um erro na busca.
     */
    public function findPacienteById($id)
    {
        try {
            $paciente = $this->pacienteModel::with(['hospital', 'planoSaude'])->find($id);
            if (!$paciente) {
                throw new Exception('Paciente não encontrado');
            }
            return $paciente;
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar o paciente: ' . $e->getMessage());
        }
    }

    /**
     * Obtém todos os pacientes paginados.
     *
     * @param int $perPage Número de pacientes por página.
     * @return LengthAwarePaginator
     */
    public function getAllPacientes($perPage = 10)
    {
        return $this->pacienteModel::select('id', 'nome')->paginate($perPage);
    }

    public function findPacientesByName($nome)
    {
        $pacientes = Paciente::where('nome', 'LIKE', '%' . $nome . '%')->get();
        return $pacientes->filter(function ($paciente) use ($nome) {
            return stripos($paciente->nome, $nome) !== false;
        });
    }
    

}
