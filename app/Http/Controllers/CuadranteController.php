<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Horario;


class CuadranteController extends Controller
{
    public function create()
    {
        return view('cuadrantes.create');
    }

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'titulo' => 'nullable|string|max:255', // Permitir nulo
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // Si no hay título, generar uno por defecto
        $titulo = $validated['titulo'] ?? null;
        if (!$titulo || trim($titulo) === '') {
            $titulo = 'Cuadrante del ' . $validated['fecha_inicio'] . ' al ' . $validated['fecha_fin'];
        }

        // Crear el horario con los datos validados
        $horario = \App\Models\Horario::create([
            'creado_por' => \Auth::id(),
            'titulo' => $titulo,
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
        ]);

        // Redirigir a la vista que muestra el horario creado
        return redirect()->route('horarios.show', $horario);
    }

    public function index()
    {
        $cuadrantes = Horario::orderBy('created_at', 'desc')->get();

        return view('cuadrantes.index', compact('cuadrantes'));
    }

    public function destroy($id)
    {
        $cuadrante = \App\Models\Horario::findOrFail($id);
        $cuadrante->delete();

        return redirect()->route('cuadrantes.index')->with('success', 'Cuadrante eliminado correctamente.');
    }

    public function modificarVista()
    {
        $cuadrantes = \App\Models\Horario::orderBy('fecha_inicio', 'desc')->get();
        return view('cuadrantes.ModCuadrante', compact('cuadrantes'));
    }

    public function edit(\App\Models\Horario $cuadrante)
    {
        return view('cuadrantes.edit', compact('cuadrante'));
    }

    public function update(Request $request, \App\Models\Horario $cuadrante)
    {
        $request->validate([
            'titulo' => 'nullable|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $defaultTitle = 'Cuadrante del ' . $cuadrante->fecha_inicio . ' al ' . $cuadrante->fecha_fin;
        $newDefaultTitle = 'Cuadrante del ' . $request->fecha_inicio . ' al ' . $request->fecha_fin;

        $titulo = $request->titulo;
        // Si el título está vacío o coincide con el formato por defecto, genera el nuevo título automático
        if (!$titulo || trim($titulo) === '' || trim($titulo) === $defaultTitle) {
            $titulo = $newDefaultTitle;
        }

        $cuadrante->update([
            'titulo' => $titulo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->route('cuadrantes.modificar')->with('success', 'Cuadrante actualizado correctamente.');
    }
}
