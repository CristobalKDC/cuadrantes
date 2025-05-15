<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\HorarioEntrada;
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
        $usuariosConEntrada = $horario->entradas()
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($entradasPorUsuario) {
                $user = $entradasPorUsuario->first()->user;
                $entradas = [];
                foreach ($entradasPorUsuario as $entrada) {
                    if ($entrada->fecha) {
                        // Si tiene fecha, agrupa por fecha
                        if (!isset($entradas[$entrada->fecha])) {
                            $entradas[$entrada->fecha] = [];
                        }
                        $entradas[$entrada->fecha][] = [
                            'hora_inicio' => $entrada->hora_inicio,
                            'hora_fin' => $entrada->hora_fin,
                        ];
                    }
                    // Si la entrada es "vacía" (fecha nula), no la agregues a ninguna fecha,
                    // pero el usuario sí debe aparecer en la tabla (con todas las fechas vacías)
                }
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'apellidos' => $user->apellidos,
                    'apodo' => $user->apodo,
                    'entradas' => $entradas,
                ];
            })
            ->values()
            ->toArray();

        $inicio = Carbon::parse($horario->fecha_inicio);
        $fin = Carbon::parse($horario->fecha_fin);

        $fechas = [];
        while ($inicio->lte($fin)) {
            $fechas[] = $inicio->copy()->toDateString(); // formato: YYYY-MM-DD
            $inicio->addDay();
        }

        $usuarios = User::orderBy('name')->get();

        return view('horarios.show', compact('horario', 'fechas', 'usuarios', 'usuariosConEntrada'));
    }

    public function guardarEntradas(Request $request, Horario $horario)
    {
        $request->validate([
            'entradas' => 'required|array',
            'entradas.*.user_id' => 'required|exists:users,id',
        ]);

        // Elimina todas las entradas actuales del cuadrante
        $horario->entradas()->delete();

        // Si no hay entradas, no hacer nada
        if (empty($request->entradas)) {
            return response()->json([
                'success' => true,
                'message' => 'No hay usuarios ni horarios para guardar'
            ])->header('Content-Type', 'application/json');
        }

        // Para cada usuario, guarda al menos una entrada vacía si no tiene horarios
        $usuariosProcesados = [];
        foreach ($request->entradas as $entrada) {
            $userId = $entrada['user_id'];
            // Si tiene horario, guarda la entrada (asegura que no sean strings vacíos)
            if (
                isset($entrada['fecha']) && $entrada['fecha'] !== null && $entrada['fecha'] !== '' &&
                isset($entrada['hora_inicio']) && $entrada['hora_inicio'] !== null && $entrada['hora_inicio'] !== '' &&
                isset($entrada['hora_fin']) && $entrada['hora_fin'] !== null && $entrada['hora_fin'] !== ''
            ) {
                HorarioEntrada::create([
                    'horario_id' => $horario->id,
                    'user_id' => $userId,
                    'fecha' => $entrada['fecha'],
                    'hora_inicio' => $entrada['hora_inicio'],
                    'hora_fin' => $entrada['hora_fin'],
                ]);
                $usuariosProcesados[$userId] = true;
            } else {
                // Si no tiene horario, solo crea una entrada vacía si no la hemos creado ya para este usuario
                if (empty($usuariosProcesados[$userId])) {
                    HorarioEntrada::create([
                        'horario_id' => $horario->id,
                        'user_id' => $userId,
                        'fecha' => null,
                        'hora_inicio' => null,
                        'hora_fin' => null,
                    ]);
                    $usuariosProcesados[$userId] = true;
                }
            }
        }

        // Forzar respuesta JSON con el header correcto y sin pretty print
        return response()->json([
            'success' => true,
            'message' => 'Usuarios y horarios guardados correctamente'
        ])->header('Content-Type', 'application/json');
    }

    public function vaciar(Horario $horario)
    {
        $horario->entradas()->delete();

        return response()->json(['success' => true]);
    }
}
