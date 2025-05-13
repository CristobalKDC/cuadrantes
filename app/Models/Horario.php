<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = [
        'creado_por',
        'titulo',
        'fecha_inicio',
        'fecha_fin',
    ];

    // Relación: el jefe que creó el horario
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    // Relación: entradas (bloques de usuario/día/hora)
    public function entradas()
    {
        return $this->hasMany(HorarioEntrada::class);
    }
}
