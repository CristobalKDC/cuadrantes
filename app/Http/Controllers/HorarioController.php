<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HorarioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $horario = Horario::create([
            'creado_por' => Auth::id(),
            'titulo' => 'Cuadrante del ' . $request->fecha_inicio . ' al ' . $request->fecha_fin,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->route('horarios.show', $horario);
    }

    public function show(Horario $horario)
    {
        $inicio = Carbon::parse($horario->fecha_inicio);
        $fin = Carbon::parse($horario->fecha_fin);

        $fechas = [];
        while ($inicio->lte($fin)) {
            $fechas[] = $inicio->copy()->toDateString(); // formato: YYYY-MM-DD
            $inicio->addDay();
        }

        $usuarios = User::orderBy('name')->get();

        return view('horarios.show', compact('horario', 'fechas', 'usuarios'));
    }
}
