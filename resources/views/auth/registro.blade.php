<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <title>Registro</title>
</head>

<body>

    <div class="contenedor">

        <h2>Registro de usuario</h2>
        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background:red;color:white;padding:10px;">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ url('/registro') }}" method="POST">
            @csrf

            <input type="text" name="nick" placeholder="Nick">

            <input type="text" name="nombre" placeholder="Nombre">

            <input type="text" name="apellidos" placeholder="Apellidos">

            <input type="email" name="email" placeholder="Email">

            <input type="password" name="password" placeholder="Contraseña">

            <button type="submit">Registrarse</button>

        </form>

    </div>

</body>

</html>