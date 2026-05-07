<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Listado de logs</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>

    <h1>Listado de logs</h1>

    <table>
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Usuario</th>
            <th>Operación</th>
        </tr>

        @foreach($logs as $log)
        <tr>
            <td>{{ $log['fecha'] }}</td>
            <td>{{ $log['hora'] }}</td>
            <td>{{ $log['usuario'] }}</td>
            <td>{{ $log['operacion'] }}</td>
        </tr>
        @endforeach

    </table>

</body>

</html>