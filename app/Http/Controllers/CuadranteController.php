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
            'titulo' => 'required|string|max:255', // Asegúrate de validar el título
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // Crear el horario con los datos validados
        $horario = Horario::create([
            'creado_por' => Auth::id(),
            'titulo' => $validated['titulo'], // Accede correctamente al valor validado
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


    
}
