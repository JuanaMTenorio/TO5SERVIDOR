<!DOCTYPE html>
<html>
<head>
    <title>Detalle categoría</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

<div class="contenedor">

    <h2>Detalle de la categoría</h2>

    <p><strong>Nombre:</strong> {{ $categoria['nombre'] }}</p>

    <a href="{{ url('/categorias') }}" class="boton">Volver</a>

</div>

</body>
</html>