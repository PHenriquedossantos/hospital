<?php

namespace App\Services;

use App\Models\PlanoSaude;

class PlanoSaudeService
{
    public function findOrCreate($nome)
    {
        return PlanoSaude::firstOrCreate(['nome' => $nome]);
    }
}
