<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Listado de entradas</title>
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

    <h1>Listado de entradas</h1>

    <table>
        <tr>
            <th>Título</th>
            <th>Categoría</th>
            <th>Descripción</th>
            <th>Fecha</th>
        </tr>

        @foreach($entradas as $entrada)
        <tr>
            <td>{{ $entrada['titulo'] }}</td>
            <td>{{ $entrada['categoria'] }}</td>
            <td>{{ $entrada['descripcion'] }}</td>
            <td>{{ $entrada['fecha'] }}</td>
        </tr>
        @endforeach
    </table>

</body>

</html>