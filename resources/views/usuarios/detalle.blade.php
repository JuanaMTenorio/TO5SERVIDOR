<!DOCTYPE html>
<html>

<head>
    <title>Detalle usuario</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>

    <div class="contenedor">

        <h2>Detalle del usuario</h2>

        <p><strong>Nick:</strong> {{ $usuario['nick'] }}</p>
        <p><strong>Nombre:</strong> {{ $usuario['nombre'] }}</p>
        <p><strong>Apellidos:</strong> {{ $usuario['apellidos'] }}</p>
        <p><strong>Email:</strong> {{ $usuario['email'] }}</p>
        <p><strong>Rol:</strong> {{ $usuario['rol'] }}</p>
        <p><strong>Imagen avatar:</strong> {{ $usuario['imagen_avatar'] }}</p>

        <a href="{{ url('/usuarios') }}" class="boton">Volver al listado</a>

    </div>

</body>

</html>