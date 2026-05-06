<!DOCTYPE html>
<html>

<head>
    <title>Panel de administración</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>
    <div class="contenedor">

        <h2>Bienvenida {{ session('usuario_nombre') }}</h2>
        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif
        <p>Has accedido a una zona privada</p>

        <div class="botonera">
            <div class="fila-botones">
                @if(session('usuario_rol') == 'administrador')
                <a href="{{ url('/registro') }}" class="boton">Crear usuario</a>
                <a href="{{ url('/usuarios') }}" class="boton">Listado usuarios</a>
                <a href="{{ url('/categorias/crear') }}" class="boton">Crear categoría</a>
                <a href="{{ url('/categorias') }}" class="boton">Listado categorías</a>
                @endif
                <a href="{{ url('/entradas/crear') }}" class="boton">Crear entrada</a>
            </div>
            <div class="fila-botones-centro">
                <a href="{{ url('/logout') }}" class="boton boton-rojo">Cerrar sesión</a>
            </div>
        </div>
        <hr>
        <h3>Listado de entradas</h3>
        @if(count($entradas) > 0)
        <table border="1" width="100%" cellpadding="5">
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Operaciones</th>
            </tr>
            @foreach($entradas as $entrada)
            <tr>
                <td>{{ $entrada['titulo'] }}</td>
                <td>{{ $entrada['categoria'] }}</td>
                <td>
                    @if($entrada['imagen'])
                    <img src="{{ asset('images/' . $entrada['imagen']) }}" width="80">
                    @endif
                </td>

                <td>{{ $entrada['descripcion'] }}</td>
                <td>{{ $entrada['fecha'] }}</td>

                <td class="acciones">

                    <a href="{{ url('/entradas/detalle/' . $entrada['id']) }}" class="btn btn-detalle">
                        Detalle
                    </a>

                    @if(session('usuario_rol') == 'administrador' || session('usuario_id') == $entrada['usuario_id'])

                    <a href="{{ url('/entradas/editar/' . $entrada['id']) }}" class="btn btn-editar">
                        Editar
                    </a>

                    <a href="{{ url('/entradas/eliminar/' . $entrada['id']) }}"
                        class="btn btn-eliminar"
                        onclick="return confirm('¿Seguro que quieres eliminar esta entrada?')">
                        Eliminar
                    </a>

                    @endif

                </td>
            </tr>
            @endforeach
        </table>
        @else
        <p>No hay entradas</p>
        @endif
    </div>

</body>

</html>