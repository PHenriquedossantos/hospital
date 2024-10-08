<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoSaude extends Model
{
    use HasFactory;

    protected $table = 'planos_saude';

    protected $fillable = [
        'nome',
    ];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
