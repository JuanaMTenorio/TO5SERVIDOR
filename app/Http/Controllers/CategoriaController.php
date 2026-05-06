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

    public function listar()
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM Categorias";
            $stmt = $conexion->query($sql);

            $categorias = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return view('categorias.listar', compact('categorias'));
        } catch (\PDOException $e) {
            return "Error al listar categorías: " . $e->getMessage();
        }
    }

    public function detalle($id)
    {
        try {
            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM Categorias WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $categoria = $stmt->fetch(\PDO::FETCH_ASSOC);

            return view('categorias.detalle', compact('categoria'));
        } catch (\PDOException $e) {
            return "Error al mostrar detalle: " . $e->getMessage();
        }
    }

    public function eliminar($id)
    {
        try {
            $conexion = \App\Config\Database::conectar();

            // Comprobar si tiene entradas
            $sqlCheck = "SELECT COUNT(*) AS total FROM Entradas WHERE categoria_id = :id";
            $stmtCheck = $conexion->prepare($sqlCheck);
            $stmtCheck->execute([':id' => $id]);

            $resultado = $stmtCheck->fetch(\PDO::FETCH_ASSOC);

            if ($resultado['total'] > 0) {
                return redirect('/categorias')
                    ->with('error', 'No se puede eliminar la categoría porque tiene entradas asociadas');
            }

            // Eliminar
            $sql = "DELETE FROM Categorias WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            return redirect('/categorias')
                ->with('success', 'Categoría eliminada correctamente');
        } catch (\PDOException $e) {
            return redirect('/categorias')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function editar($id)
    {
        try {
            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM Categorias WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $categoria = $stmt->fetch(\PDO::FETCH_ASSOC);

            return view('categorias.editar', compact('categoria'));
        } catch (\PDOException $e) {
            return "Error al editar: " . $e->getMessage();
        }
    }

    public function actualizar(Request $request, $id)
    {
        try {
            $conexion = \App\Config\Database::conectar();

            $nombre = trim(strip_tags($request->nombre));

            if (empty($nombre)) {
                return redirect('/categorias/editar/' . $id)
                    ->with('error', 'El nombre es obligatorio');
            }

            $sql = "UPDATE Categorias SET nombre = :nombre WHERE id = :id";
            $stmt = $conexion->prepare($sql);

            $stmt->execute([
                ':nombre' => $nombre,
                ':id' => $id
            ]);

            return redirect('/categorias')->with('success', 'Categoría actualizada');
        } catch (\PDOException $e) {
            return redirect('/categorias')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
