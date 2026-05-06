<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebaConexionController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/panel', function () {
    if (!session()->has('usuario_id')) {
        return redirect('/login')->with('error', 'Debes iniciar sesión');
    }
    return view('panel');
});

Route::get('/probar-conexion', [PruebaConexionController::class, 'index']);

Route::get('/registro', [AuthController::class, 'mostrarRegistro']);
Route::post('/registro', [AuthController::class, 'registrar']);

Route::get('/login', [AuthController::class, 'mostrarLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);
