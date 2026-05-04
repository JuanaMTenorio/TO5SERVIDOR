<?php

namespace App\Http\Controllers;

use App\Config\Database;

class PruebaConexionController extends Controller
{
    public function index()
    {
        $conexion = Database::conectar();

        return "Conexión realizada correctamente con PDO";
    }
}
