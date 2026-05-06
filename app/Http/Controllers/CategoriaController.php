<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config\Database;

class CategoriaController extends Controller
{
    public function crear()
    {
        return view('categorias.crear');
    }

    public function guardar(Request $request)
    {
        try {
            // 1 Recogemos el nombre enviado desde el formulario
            $nombre = $request->nombre;
            // 2 Sanitizamos: quitamos espacios y etiquetas HTML
            $nombre = trim($nombre);
            $nombre = strip_tags($nombre);
            // 3 Validamos que no esté vacío
            if (empty($nombre)) {
                return redirect('/categorias/crear')
                    ->with('error', 'El nombre de la categoría es obligatorio');
            }
            // 4 Conectamos con la base de datos usando PDO
            $conexion = Database::conectar();
            // 5 Preparamos la consulta
            $sql = "INSERT INTO Categorias (nombre) VALUES (:nombre)";
            $stmt = $conexion->prepare($sql);
            // 6 Ejecutamos la consulta
            $stmt->execute([
                ':nombre' => $nombre
            ]);
            // 7 Volvemos al panel con mensaje correcto
            return redirect('/panel')
                ->with('success', 'Categoría guardada correctamente');
        } catch (\PDOException $e) {
            return redirect('/categorias/crear')
                ->with('error', 'Error al guardar la categoría: ' . $e->getMessage());
        }
    }
}
