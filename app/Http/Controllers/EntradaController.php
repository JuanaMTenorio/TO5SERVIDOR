<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

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
            $imagenNombre = null;

            if ($request->hasFile('imagen')) {
                $imagenArchivo = $request->file('imagen');

                // Generar nombre único
                $imagenNombre = time() . "_" . $imagenArchivo->getClientOriginalName();

                // Guardar en carpeta images
                $imagenArchivo->move(public_path('images'), $imagenNombre);
            }

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
                ':imagen' => $imagenNombre,
                ':descripcion' => $descripcion,
                ':fecha' => $fecha
            ]);

            // Guardo el logs
            $sqlLog = "CALL insertar_log(:usuario, :operacion)";
            $stmtLog = $conexion->prepare($sqlLog);

            $stmtLog->execute([
                ':usuario' => session('usuario_nombre'),
                ':operacion' => 'Crear entrada'
            ]);

            // 6. Volvemos al panel
            return redirect('/panel')->with('success', 'Entrada guardada correctamente');
        } catch (\PDOException $e) {
            return redirect('/entradas/crear')
                ->with('error', 'Error al guardar la entrada: ' . $e->getMessage());
        }
    }

    public function panel(Request $request)
    {
        try {
            // 1. Proteger acceso
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            // Busqueda
            $buscar = $request->buscar ?? '';

            // 2. Página actual
            $pagina = $request->pagina ?? 1;

            // 3. Registros por página
            $registrosPorPagina = $request->registros ?? 5;

            // 4. Calcular desde qué registro empieza
            $inicio = ($pagina - 1) * $registrosPorPagina;

            // 5. Contar el total de entradas
            $sqlTotal = "SELECT COUNT(*) AS total 
             FROM Entradas 
             WHERE titulo LIKE :buscar 
                OR descripcion LIKE :buscar";

            $stmtTotal = $conexion->prepare($sqlTotal);
            $stmtTotal->execute([
                ':buscar' => '%' . $buscar . '%'
            ]);

            $resultadoTotal = $stmtTotal->fetch(\PDO::FETCH_ASSOC);
            $totalEntradas = $resultadoTotal['total'];

            // 6. Calcular total de páginas
            $totalPaginas = ceil($totalEntradas / $registrosPorPagina);

            $orden = $request->orden ?? 'desc';

            if ($orden != 'asc' && $orden != 'desc') {
                $orden = 'desc';
            }
            // 7. Obtener solo las entradas de la página actual
            $sql = "SELECT e.*, c.nombre AS categoria 
                FROM Entradas e
                INNER JOIN Categorias c ON e.categoria_id = c.id
                WHERE e.titulo LIKE :buscar 
                OR e.descripcion LIKE :buscar
                ORDER BY e.fecha $orden
                LIMIT :inicio, :registros";

            $stmt = $conexion->prepare($sql);

            $stmt->bindValue(':inicio', (int)$inicio, \PDO::PARAM_INT);
            $stmt->bindValue(':registros', (int)$registrosPorPagina, \PDO::PARAM_INT);

            $stmt->bindValue(':buscar', '%' . $buscar . '%', \PDO::PARAM_STR);
            $stmt->execute();

            $entradas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return view('panel', compact(
                'entradas',
                'pagina',
                'registrosPorPagina',
                'totalPaginas',
                'totalEntradas',
                'orden'
            ));
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

            // 1. Buscar la entrada antes de eliminarla
            $sqlEntrada = "SELECT * FROM Entradas WHERE id = :id";
            $stmtEntrada = $conexion->prepare($sqlEntrada);
            $stmtEntrada->execute([':id' => $id]);

            $entrada = $stmtEntrada->fetch(\PDO::FETCH_ASSOC);
            // 2. Si la entrada no existe
            if (!$entrada) {
                return redirect('/panel')->with('error', 'La entrada no existe');
            }
            // 3. Comprobar permisos
            if (
                session('usuario_rol') != 'administrador' &&
                session('usuario_id') != $entrada['usuario_id']
            ) {
                return redirect('/panel')->with('error', 'No tienes permisos para eliminar esta entrada');
            }
            // 4. Eliminar entrada
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
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT * FROM Entradas WHERE id = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([':id' => $id]);

            $entrada = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$entrada) {
                return redirect('/panel')->with('error', 'La entrada no existe');
            }

            if (
                session('usuario_rol') != 'administrador' &&
                session('usuario_id') != $entrada['usuario_id']
            ) {
                return redirect('/panel')->with('error', 'No tienes permisos para editar esta entrada');
            }

            $sqlCat = "SELECT * FROM Categorias";
            $stmtCat = $conexion->query($sqlCat);
            $categorias = $stmtCat->fetchAll(\PDO::FETCH_ASSOC);

            return view('entradas.editar', compact('entrada', 'categorias'));
        } catch (\PDOException $e) {
            return redirect('/panel')->with('error', 'Error al editar: ' . $e->getMessage());
        }
    }

    public function actualizar(Request $request, $id)
    {
        try {
            if (!session()->has('usuario_id')) {
                return redirect('/login')->with('error', 'Debes iniciar sesión');
            }

            $conexion = \App\Config\Database::conectar();

            $sqlEntrada = "SELECT * FROM Entradas WHERE id = :id";
            $stmtEntrada = $conexion->prepare($sqlEntrada);
            $stmtEntrada->execute([':id' => $id]);

            $entrada = $stmtEntrada->fetch(\PDO::FETCH_ASSOC);

            if (!$entrada) {
                return redirect('/panel')->with('error', 'La entrada no existe');
            }

            if (
                session('usuario_rol') != 'administrador' &&
                session('usuario_id') != $entrada['usuario_id']
            ) {
                return redirect('/panel')->with('error', 'No tienes permisos para actualizar esta entrada');
            }

            $titulo = trim(strip_tags($request->titulo));
            $imagen = $entrada['imagen']; // mantenemos la imagen actual

            if ($request->hasFile('imagen')) {
                $imagenArchivo = $request->file('imagen');
                $imagen = time() . "_" . $imagenArchivo->getClientOriginalName();
                $imagenArchivo->move(public_path('images'), $imagen);
            }

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

    public function pdf()
    {
        try {
            $conexion = \App\Config\Database::conectar();

            $sql = "SELECT e.*, c.nombre AS categoria
                FROM Entradas e
                INNER JOIN Categorias c ON e.categoria_id = c.id
                ORDER BY e.fecha DESC";

            $stmt = $conexion->query($sql);
            $entradas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $pdf = Pdf::loadView('entradas.pdf', compact('entradas'));

            return $pdf->download('listado_entradas.pdf');
        } catch (\PDOException $e) {
            return redirect('/panel')->with('error', 'Error al generar PDF: ' . $e->getMessage());
        }
    }
}
