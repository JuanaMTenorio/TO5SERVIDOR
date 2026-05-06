<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebaConexionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\UsuarioController;


Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/panel', function () {
    if (!session()->has('usuario_id')) {
        return redirect('/login')->with('error', 'Debes iniciar sesión');
    }
    return view('panel');
});*/

Route::get('/panel', [EntradaController::class, 'panel']);

Route::get('/probar-conexion', [PruebaConexionController::class, 'index']);

Route::get('/registro', [AuthController::class, 'mostrarRegistro']);
Route::post('/registro', [AuthController::class, 'registrar']);

Route::get('/login', [AuthController::class, 'mostrarLogin']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/categorias/crear', [CategoriaController::class, 'crear']);
Route::post('/categorias/guardar', [CategoriaController::class, 'guardar']);

Route::get('/entradas/crear', [EntradaController::class, 'crear']);
Route::post('/entradas/guardar', [EntradaController::class, 'guardar']);

Route::get('/entradas/detalle/{id}', [EntradaController::class, 'detalle']);
Route::get('/entradas/eliminar/{id}', [EntradaController::class, 'eliminar']);

Route::get('/entradas/editar/{id}', [EntradaController::class, 'editar']);
Route::post('/entradas/actualizar/{id}', [EntradaController::class, 'actualizar']);

Route::get('/usuarios', [UsuarioController::class, 'listar']);
Route::get('/usuarios/detalle/{id}', [UsuarioController::class, 'detalle']);
Route::get('/usuarios/editar/{id}', [UsuarioController::class, 'editar']);
Route::post('/usuarios/actualizar/{id}', [UsuarioController::class, 'actualizar']);
Route::get('/usuarios/eliminar/{id}', [UsuarioController::class, 'eliminar']);

Route::get('/categorias', [CategoriaController::class, 'listar']);
Route::get('/categorias/detalle/{id}', [CategoriaController::class, 'detalle']);
Route::get('/categorias/eliminar/{id}', [CategoriaController::class, 'eliminar']);
Route::get('/categorias/editar/{id}', [CategoriaController::class, 'editar']);
Route::post('/categorias/actualizar/{id}', [CategoriaController::class, 'actualizar']);
