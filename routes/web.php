<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CuadranteController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HorarioEntradaController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest'])
    ->name('login');

Route::get('/usuario', function () {
    return view('vistaUsuario');
})->middleware(['auth', 'verified'])->name('vista.usuario');

//Registro de usuarios, redireccion
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest'])
    ->name('register');

// Rutas para cuadrantes 
Route::get('/cuadrantes/crear', [CuadranteController::class, 'create'])->name('cuadrantes.create');
Route::post('/cuadrantes/store', [CuadranteController::class, 'store'])->name('cuadrantes.store');
Route::get('/cuadrantes', [\App\Http\Controllers\CuadranteController::class, 'index'])->name('cuadrantes.index'); //Listar los cuadrantes
Route::delete('/cuadrantes/{id}', [\App\Http\Controllers\CuadranteController::class, 'destroy'])->name('cuadrantes.destroy');
Route::get('/cuadrantes/modificar', [CuadranteController::class, 'modificarVista'])->name('cuadrantes.modificar');
Route::get('/cuadrantes/{cuadrante}/editar', [CuadranteController::class, 'edit'])->name('cuadrantes.edit');
Route::put('/cuadrantes/{cuadrante}', [CuadranteController::class, 'update'])->name('cuadrantes.update');

Route::post('/horarios/{horario}/entradas/guardar', [HorarioEntradaController::class, 'guardar'])->name('horarios.entradas.guardar');

Route::delete('/horarios/{horario}/vaciar', [HorarioController::class, 'vaciar'])->name('horarios.vaciar');

Route::resource('horarios', HorarioController::class);

