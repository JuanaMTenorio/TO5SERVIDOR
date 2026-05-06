<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntradaController extends Controller
{
    public function crear()
    {
        try {
            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM Categorias";
            $stmt = $conexion->query($sql);

            $categorias = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return view('entradas.crear', compact('categorias'));
        } catch (\PDOException $e) {
            return "Error al cargar categorías: " . $e->getMessage();
        }
    }
    public function guardar(Request $request)
    {
        try {
            // 1. Comprobamos que hay usuario logueado
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }
            // 2. Recogemos y limpiamos datos
            $usuario_id = session('usuario_id');
            $categoria_id = $request->categoria_id;
            $titulo = trim(strip_tags($request->titulo));
            $imagen = trim(strip_tags($request->imagen));
            $descripcion = trim(strip_tags($request->descripcion));
            $fecha = $request->fecha;
            // 3. Validación básica
            if (empty($categoria_id) || empty($titulo) || empty($descripcion) || empty($fecha)) {
                return redirect('/entradas/crear')
                    ->with('error', 'Todos los campos obligatorios deben estar completos');
            }
            // 4. Conexión PDO
            $conexion = \App\Config\Database::conectar();
            // 5. Insertar entrada
            $sql = "INSERT INTO Entradas 
                (usuario_id, categoria_id, titulo, imagen, descripcion, fecha)
                VALUES 
                (:usuario_id, :categoria_id, :titulo, :imagen, :descripcion, :fecha)";

            $stmt = $conexion->prepare($sql);

            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':categoria_id' => $categoria_id,
                ':titulo' => $titulo,
                ':imagen' => $imagen,
                ':descripcion' => $descripcion,
                ':fecha' => $fecha
            ]);
            // 6. Volvemos al panel
            return redirect('/panel')->with('success', 'Entrada guardada correctamente');
        } catch (\PDOException $e) {
            return redirect('/entradas/crear')
                ->with('error', 'Error al guardar la entrada: ' . $e->getMessage());
        }
    }

    public function panel()
    {
        try {
            // PROTEGER ACCESO
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            // JOIN entradas + categorias
            $sql = "SELECT e.*, c.nombre AS categoria 
                FROM Entradas e
                INNER JOIN Categorias c ON e.categoria_id = c.id
                ORDER BY e.fecha DESC";

            $stmt = $conexion->query($sql);
            $entradas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return view('panel', compact('entradas'));
        } catch (\PDOException $e) {
            return "Error al cargar entradas: " . $e->getMessage();
        }
    }

    public function detalle($id)
    {
        try {
            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT e.*, c.nombre AS categoria
                FROM Entradas e
                INNER JOIN Categorias c ON e.categoria_id = c.id
                WHERE e.id = :id";

            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $entrada = $stmt->fetch(\PDO::FETCH_ASSOC);

            return view('entradas.detalle', compact('entrada'));
        } catch (\PDOException $e) {
            return "Error al mostrar detalle: " . $e->getMessage();
        }
    }

    public function eliminar($id)
    {
        try {
            // Seguridad: comprobar sesión
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "DELETE FROM Entradas WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            return redirect('/panel')->with('success', 'Entrada eliminada correctamente');
        } catch (\PDOException $e) {
            return redirect('/panel')->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function editar($id)
    {
        try {
            $conexion = \App\Config\Database::conectar();

            // Obtener entrada
            $sql = "SELECT * FROM Entradas WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $entrada = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Obtener categorías
            $sqlCat = "SELECT * FROM Categorias";
            $stmtCat = $conexion->query($sqlCat);
            $categorias = $stmtCat->fetchAll(\PDO::FETCH_ASSOC);

            return view('entradas.editar', compact('entrada', 'categorias'));
        } catch (\PDOException $e) {
            return "Error al editar: " . $e->getMessage();
        }
    }

    public function actualizar(Request $request, $id)
{
    try {
        $conexion = \App\Config\Database::conectar();

        $titulo = trim(strip_tags($request->titulo));
        $imagen = trim(strip_tags($request->imagen));
        $descripcion = trim(strip_tags($request->descripcion));
        $fecha = $request->fecha;
        $categoria_id = $request->categoria_id;

        $sql = "UPDATE Entradas 
                SET titulo = :titulo,
                    imagen = :imagen,
                    descripcion = :descripcion,
                    fecha = :fecha,
                    categoria_id = :categoria_id
                WHERE id = :id";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ':titulo' => $titulo,
            ':imagen' => $imagen,
            ':descripcion' => $descripcion,
            ':fecha' => $fecha,
            ':categoria_id' => $categoria_id,
            ':id' => $id
        ]);

        return redirect('/panel')->with('success', 'Entrada actualizada correctamente');

    } catch (\PDOException $e) {
        return redirect('/panel')->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
}

    
}
