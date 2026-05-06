<!DOCTYPE html>
<html>
<head>
    <title>Detalle entrada</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

<div class="contenedor">

    <h2>Detalle de la entrada</h2>

    <p><strong>Título:</strong> {{ $entrada['titulo'] }}</p>

    <p><strong>Categoría:</strong> {{ $entrada['categoria'] }}</p>

    <p><strong>Imagen:</strong> {{ $entrada['imagen'] }}</p>

    <p><strong>Descripción:</strong> {{ $entrada['descripcion'] }}</p>

    <p><strong>Fecha:</strong> {{ $entrada['fecha'] }}</p>

    <br>

    <a href="{{ url('/panel') }}" class="boton">Volver al panel</a>

</div>

</body>
</html>