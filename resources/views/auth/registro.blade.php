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

            <label>Introduce el código:</label><br>

            <div style="background:#ddd;padding:10px;font-weight:bold;letter-spacing:3px;">
                {{ $captcha }}
            </div>
            <input type="text" name="captcha_usuario" placeholder="Escribe el código">
            <br><br>


            <button type="submit">Registrarse</button>

        </form>
        <br>
        <a href="{{ url('/panel') }}" class="boton">Volver al panel</a>
    </div>

</body>

</html>