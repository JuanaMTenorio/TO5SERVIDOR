<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
    <title>Login</title>
</head>

<body>
    <div class="contenedor">

        <h2>Iniciar sesión</h2>
        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        <p></p>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <label>Email:</label><br>
            <input type="email" name="email" value="{{ $_COOKIE['email'] ?? '' }}">

            <label>Contraseña:</label><br>
            <input type="password" name="password"><br><br>

            <div class="checkbox-group">
                <input type="checkbox" name="recordar" id="recordar">
                <label for="recordar">Recordar usuario</label>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="mantener_sesion" id="mantener">
                <label for="mantener">Mantener sesión abierta</label>
            </div>

            <label>Introduce el código:</label><br>
            <div style="background:#ddd;padding:10px;font-weight:bold;letter-spacing:3px;">
                {{ $captcha }}
            </div>

            <input type="text" name="captcha_usuario" placeholder="Escribe el código"><br><br>
            <button type="submit">Entrar</button>

        </form>

</body>

</html>