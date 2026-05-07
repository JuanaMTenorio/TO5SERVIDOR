<!DOCTYPE html>
<html>

<head>
    <title>Gestión de logs</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>

    <div class="contenedor">

        <h2>Gestión de logs</h2>

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

        @if(count($logs) > 0)
        <a href="{{ url('/logs/pdf') }}" class="boton">Imprimir PDF</a>
        <p></p>
        <a href="{{ url('/usuarios/exportar') }}" class="boton">Exportar usuarios Excel</a>
        <p></p>
        <table border="1" width="100%" cellpadding="5">
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Usuario</th>
                <th>Operación</th>
                <th>Operaciones</th>
            </tr>

            @foreach($logs as $log)
            <tr>
                <td>{{ $log['fecha'] }}</td>
                <td>{{ $log['hora'] }}</td>
                <td>{{ $log['usuario'] }}</td>
                <td>{{ $log['operacion'] }}</td>
                <td class="acciones">
                    <a href="{{ url('/logs/eliminar/' . $log['id']) }}"
                        class="btn btn-eliminar"
                        onclick="return confirm('¿Seguro que quieres eliminar este log?')">
                        Eliminar
                    </a>
                </td>
            </tr>
            @endforeach
        </table>

        @else
        <p>No hay registros de logs.</p>
        @endif

        <br>
        <a href="{{ url('/panel') }}" class="boton">Volver al panel</a>

    </div>

</body>

</html>