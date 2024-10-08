<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'nome',
        'hospital_id',
        'plano_saude_id',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function planoSaude()
    {
        return $this->belongsTo(PlanoSaude::class);
    }
}
