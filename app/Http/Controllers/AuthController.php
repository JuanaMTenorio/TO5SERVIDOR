<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;




class AuthController extends Controller
{

    public function mostrarRegistro()
    {
        return view('auth.registro');
    }

    public function registrar(Request $request)
    {
        try {
            // 1. Recoger datos del formulario
            $nick = $request->nick;
            $nombre = $request->nombre;
            $apellidos = $request->apellidos;
            $email = $request->email;
            $password = $request->password;
            // 2. Encriptar la contraseña (MUY IMPORTANTE)
            $password_encriptada = password_hash($password, PASSWORD_DEFAULT);
            // 3. Conectar con la BD usando PDO
            $conexion = \App\Config\Database::conectar();
            // 4. Preparar consulta SQL
            $sql = "INSERT INTO Usuarios (nick, nombre, apellidos, email, password, rol)
                VALUES (:nick, :nombre, :apellidos, :email, :password, :rol)";

            $stmt = $conexion->prepare($sql);
            // 5. Ejecutar con parámetros
            $stmt->execute([
                ':nick' => $nick,
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':password' => $password_encriptada,
                ':rol' => 'usuario'
            ]);
            return redirect('/registro')->with('success', 'Usuario registrado correctamente');
        } catch (\PDOException $e) {
            return redirect('/registro')->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    public function mostrarLogin()
    {
        return view('auth.login');
    }



    public function login(Request $request)
    {
        try {
            // 1. Recoger datos del formulario
            $email = $request->email;
            $password = $request->password;
            // 2. Conectar a la BD
            $conexion = \App\Config\Database::conectar();
            // 3. Buscar usuario por email
            $sql = "SELECT * FROM Usuarios WHERE email = :email";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':email' => $email]);

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
            // 4. Comprobar si existe el usuario
            if ($usuario) {
                // 5. Verificar contraseña
                if (password_verify($password, $usuario['password'])) {
                    // 6. Crear sesión
                    session([
                        'usuario_id' => $usuario['id'],
                        'usuario_nombre' => $usuario['nombre'],
                        'usuario_rol' => $usuario['rol']
                    ]);
                    return redirect('/panel');
                    // RECORDAR USUARIO
                    if ($request->has('recordar')) {
                        setcookie('email', $email, time() + (86400 * 30), "/MiPequeñoBlogLaravel/public");
                    } else {
                        setcookie('email', '', time() - 3600, "/MiPequeñoBlogLaravel/public");
                    }
                } else {
                    return "Contraseña incorrecta";
                }
            } else {
                return "Usuario no encontrado";
            }
        } catch (\PDOException $e) {
            return "Error en login: " . $e->getMessage();
        }
    }

    public function logout()
    {
        // Eliminamos todos los datos guardados en sesión
        session()->flush();

        // Redirigimos al login con un mensaje
        return redirect('/login')->with('success', 'Sesión cerrada correctamente');
    }
}
