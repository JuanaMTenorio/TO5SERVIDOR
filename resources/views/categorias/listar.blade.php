<!DOCTYPE html>
<html>

<head>
    <title>Listado de categorías</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>

    <div class="contenedor">
        <h2>Listado de categorías</h2>
        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
        @endif
        @if(count($categorias) > 0)
        <table border="1" width="100%" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Operaciones</th>
            </tr>
            @foreach($categorias as $categoria)
            <tr>
                <td>{{ $categoria['nombre'] }}</td>
                <td class="acciones">
                    <a href="{{ url('/categorias/detalle/' . $categoria['id']) }}" class="btn btn-detalle">
                        Detalle
                    </a>
                    <a href="{{ url('/categorias/editar/' . $categoria['id']) }}" class="btn btn-editar">
                        Editar
                    </a>
                    <a href="{{ url('/categorias/eliminar/' . $categoria['id']) }}"
                        class="btn btn-eliminar"
                        onclick="return confirm('¿Seguro que quieres eliminar esta categoría?')">
                        Eliminar
                    </a>

                </td>
            </tr>
            @endforeach

        </table>

        @else
        <p>No hay categorías</p>
        @endif

        <br>
        <a href="{{ url('/panel') }}" class="boton">Volver</a>

    </div>

</body>

</html>