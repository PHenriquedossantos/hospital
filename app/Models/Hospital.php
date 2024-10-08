<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $table = 'hospitais';

    protected $fillable = [
        'nome',
    ];

    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}
