<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PruebaConexionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/probar-conexion', [PruebaConexionController::class, 'index']);
