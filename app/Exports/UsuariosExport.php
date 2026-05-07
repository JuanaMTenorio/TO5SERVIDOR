<?php

namespace App\Exports;

use App\Config\Database;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsuariosExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $conexion = Database::conectar();

        $sql = "SELECT id, nick, nombre, apellidos, email, rol, imagen_avatar FROM Usuarios";
        $stmt = $conexion->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nick',
            'Nombre',
            'Apellidos',
            'Email',
            'Rol',
            'Imagen Avatar'
        ];
    }
}