<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioEntrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'horario_id',
        'user_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'nota',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
