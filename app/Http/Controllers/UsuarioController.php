<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exports\UsuariosExport;
use Maatwebsite\Excel\Facades\Excel;

class UsuarioController extends Controller
{


    public function listar(Request $request)
    {
        try {
            // Seguridad
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            $buscar = $request->buscar ?? '';

            $sql = "SELECT * FROM Usuarios
                WHERE nick LIKE :buscar
                OR nombre LIKE :buscar 
                OR email LIKE :buscar";

            $stmt = $conexion->prepare($sql);

            $stmt->execute([
                ':buscar' => '%' . $buscar . '%'
            ]);

            $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return view('usuarios.listar', compact('usuarios'));
        } catch (\PDOException $e) {
            return "Error al listar usuarios: " . $e->getMessage();
        }
    }

    public function detalle($id)
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM Usuarios WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            return view('usuarios.detalle', compact('usuario'));
        } catch (\PDOException $e) {
            return "Error al mostrar detalle: " . $e->getMessage();
        }
    }

    public function editar($id)
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM Usuarios WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            return view('usuarios.editar', compact('usuario'));
        } catch (\PDOException $e) {
            return "Error al cargar usuario: " . $e->getMessage();
        }
    }

    public function actualizar(Request $request, $id)
    {
        try {
            $conexion = \App\Config\Database::conectar();

            // 1. Recoger y sanitizar datos
            $nick = trim(strip_tags($request->nick));
            $nombre = trim(strip_tags($request->nombre));
            $apellidos = trim(strip_tags($request->apellidos));
            $email = trim(strip_tags($request->email));
            $rol = $request->rol;
            $imagen_avatar = trim(strip_tags($request->imagen_avatar));

            // 2. Validación básica
            if (empty($nick) || empty($nombre) || empty($email)) {
                return redirect('/usuarios/editar/' . $id)
                    ->with('error', 'Nick, nombre y email son obligatorios');
            }

            // 3. Update
            $sql = "UPDATE Usuarios 
                SET nick = :nick,
                    nombre = :nombre,
                    apellidos = :apellidos,
                    email = :email,
                    rol = :rol,
                    imagen_avatar = :imagen_avatar
                WHERE id = :id";

            $stmt = $conexion->prepare($sql);

            $stmt->execute([
                ':nick' => $nick,
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':rol' => $rol,
                ':imagen_avatar' => $imagen_avatar,
                ':id' => $id
            ]);

            return redirect('/usuarios')->with('success', 'Usuario actualizado correctamente');
        } catch (\PDOException $e) {
            return redirect('/usuarios')
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }


    public function eliminar($id)
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            /*
            Antes de eliminar un usuario, comprobamos si tiene entradas.
            El enunciado dice que no se podrán eliminar registros
            que tengan registros relacionados.
        */
            $sqlComprobar = "SELECT COUNT(*) AS total FROM Entradas WHERE usuario_id = :id";
            $stmtComprobar = $conexion->prepare($sqlComprobar);
            $stmtComprobar->execute([':id' => $id]);
            $resultado = $stmtComprobar->fetch(\PDO::FETCH_ASSOC);

            if ($resultado['total'] > 0) {
                return redirect('/usuarios')
                    ->with('error', 'No se puede eliminar el usuario porque tiene entradas asociadas');
            }

            $sql = "DELETE FROM Usuarios WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            return redirect('/usuarios')
                ->with('success', 'Usuario eliminado correctamente');
        } catch (\PDOException $e) {
            return redirect('/usuarios')
                ->with('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }



    public function exportar()
    {
        if (!session()->has('usuario_id')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión');
        }

        if (session('usuario_rol') != 'administrador') {
            return redirect('/panel')->with('error', 'No tienes permisos');
        }

        return Excel::download(new UsuariosExport, 'usuarios.xlsx');
    }
}
