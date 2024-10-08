<?php

// app/Services/HospitalService.php

namespace App\Services;

use App\Models\Hospital;

class HospitalService
{
    public function findOrCreate($nome)
    {
        return Hospital::firstOrCreate(['nome' => $nome]);
    }
}
