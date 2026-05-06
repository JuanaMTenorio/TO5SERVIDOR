<!DOCTYPE html>
<html>

<head>
    <title>Listado de usuarios</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>
    <div class="contenedor">
        <h2>Listado de usuarios</h2>
        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
        @endif
        @if(count($usuarios) > 0)
        <table border="1" width="100%" cellpadding="5">
            <tr>
                <th>Nick</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Operaciones</th>
            </tr>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario['nick'] }}</td>
                <td>{{ $usuario['nombre'] }}</td>
                <td>{{ $usuario['email'] }}</td>
                <td>{{ $usuario['rol'] }}</td>
                <td class="acciones">
                    <a href="{{ url('/usuarios/detalle/' . $usuario['id']) }}" class="btn btn-detalle">Detalle</a>
                    <a href="{{ url('/usuarios/editar/' . $usuario['id']) }}" class="btn btn-editar">Editar</a>
                    <a href="{{ url('/usuarios/eliminar/' . $usuario['id']) }}"
                        class="btn btn-eliminar"
                        onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
                        Eliminar
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <p>No hay usuarios</p>
        @endif
        <br>
        <a href="{{ url('/panel') }}" class="boton">Volver</a>
    </div>
</body>

</html>