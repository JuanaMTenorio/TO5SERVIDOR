<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    public static function conectar()
    {
        try {
            // Tomamos los datos desde el archivo .env de Laravel
            $host = env('DB_HOST');
            $bd = env('DB_DATABASE');
            $usuario = env('DB_USERNAME');
            $password = env('DB_PASSWORD');

            // Cadena de conexión para MySQL
            $dsn = "mysql:host=$host;dbname=$bd;charset=utf8mb4";

            // Creamos la conexión PDO
            $conexion = new PDO($dsn, $usuario, $password);

            // Activamos el modo de errores por excepciones
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conexion;
        } catch (PDOException $e) {
            // Si hay error, detenemos y mostramos mensaje
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
}
