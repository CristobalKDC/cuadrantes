<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Requests\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        // Valida las credenciales
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ]);
        }

        // Regenerar la sesión para evitar ataques de fijación de sesión
        $request->session()->regenerate();

        // Redirige según el rol del usuario
        return redirect()->intended(
            auth()->user()->es_jefe ? route('dashboard') : route('vista.usuario')
        );
    }
}
