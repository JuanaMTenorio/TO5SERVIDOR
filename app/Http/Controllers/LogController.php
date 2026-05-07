<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LogController extends Controller
{
    public function listar()
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            if (session('usuario_rol') != 'administrador') {
                return redirect('/panel')->with('error', 'No tienes permisos para acceder a los logs');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM logs ORDER BY fecha DESC, hora DESC";
            $stmt = $conexion->query($sql);

            $logs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return view('logs.listar', compact('logs'));
        } catch (\PDOException $e) {
            return redirect('/panel')->with('error', 'Error al cargar logs: ' . $e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            if (session('usuario_rol') != 'administrador') {
                return redirect('/panel')->with('error', 'No tienes permisos para eliminar logs');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "DELETE FROM logs WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            return redirect('/logs')->with('success', 'Log eliminado correctamente');
        } catch (\PDOException $e) {
            return redirect('/logs')->with('error', 'Error al eliminar log: ' . $e->getMessage());
        }
    }

    public function pdf()
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            if (session('usuario_rol') != 'administrador') {
                return redirect('/panel')->with('error', 'No tienes permisos');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM logs ORDER BY fecha DESC, hora DESC";
            $stmt = $conexion->query($sql);

            $logs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $pdf = Pdf::loadView('logs.pdf', compact('logs'));

            return $pdf->download('listado_logs.pdf');
        } catch (\PDOException $e) {
            return redirect('/logs')->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }
}
