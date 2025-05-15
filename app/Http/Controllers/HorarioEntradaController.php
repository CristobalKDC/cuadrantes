<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HorarioEntrada;
use Illuminate\Support\Facades\Validator;

class HorarioEntradaController extends Controller
{
    public function guardar(Request $request, $horarioId)
    {
        $request->validate([
            'entradas' => 'required|array',
            'entradas.*.user_id' => 'required|exists:users,id',
            'entradas.*.fecha' => 'required|date',
            'entradas.*.hora_inicio' => 'required',
            'entradas.*.hora_fin' => 'required',
        ]);

        // Obtener todas las entradas actuales para este cuadrante
        $entradasActuales = \App\Models\HorarioEntrada::where('horario_id', $horarioId)->get();

        // Crear un array de claves únicas para las entradas enviadas
        $clavesNuevas = [];
        foreach ($request->entradas as $entrada) {
            $clave = $entrada['user_id'] . '|' . $entrada['fecha'] . '|' . $entrada['hora_inicio'] . '|' . $entrada['hora_fin'];
            $clavesNuevas[$clave] = $entrada;
        }

        // Eliminar las entradas que ya no están en el payload
        foreach ($entradasActuales as $actual) {
            $claveActual = $actual->user_id . '|' . $actual->fecha . '|' . $actual->hora_inicio . '|' . $actual->hora_fin;
            if (!isset($clavesNuevas[$claveActual])) {
                $actual->delete();
            }
        }

        // Insertar solo las nuevas (si no existen)
        foreach ($request->entradas as $entrada) {
            \App\Models\HorarioEntrada::firstOrCreate([
                'horario_id' => $horarioId,
                'user_id' => $entrada['user_id'],
                'fecha' => $entrada['fecha'],
                'hora_inicio' => $entrada['hora_inicio'],
                'hora_fin' => $entrada['hora_fin'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Horarios guardados correctamente'
        ]);
    }
}
