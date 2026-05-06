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

            <label>
                <input type="checkbox" name="recordar"> Recordar usuario
            </label>
            <br><br>

            <button type="submit">Entrar</button>

        </form>

</body>

</html>