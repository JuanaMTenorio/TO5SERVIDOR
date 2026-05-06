<!DOCTYPE html>
<html>
<head>
    <title>Panel</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

<div class="contenedor">

    <h2>Bienvenida {{ session('usuario_nombre') }}</h2>

    <p>Has accedido a una zona privada</p>

    <a href="{{ url('/logout') }}">Cerrar sesión</a>

</div>

</body>
</html>