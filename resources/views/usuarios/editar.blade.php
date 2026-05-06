<!DOCTYPE html>
<html>

<head>
    <title>Editar usuario</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>
    <div class="contenedor">
        <h2>Editar usuario</h2>
        <form action="{{ url('/usuarios/actualizar/' . $usuario['id']) }}" method="POST">
            @csrf

            <input type="text" name="nick" value="{{ $usuario['nick'] }}">
            <input type="text" name="nombre" value="{{ $usuario['nombre'] }}">
            <input type="text" name="apellidos" value="{{ $usuario['apellidos'] }}">
            <input type="email" name="email" value="{{ $usuario['email'] }}">
            <select name="rol">
                <option value="usuario" {{ $usuario['rol'] == 'usuario' ? 'selected' : '' }}>Usuario</option>
                <option value="administrador" {{ $usuario['rol'] == 'administrador' ? 'selected' : '' }}>Administrador</option>
            </select>
            <input type="text" name="imagen_avatar" value="{{ $usuario['imagen_avatar'] }}">
            <button type="submit">Actualizar usuario</button>
        </form>
        <br>
        <a href="{{ url('/usuarios') }}" class="boton">Volver al listado</a>
    </div>
</body>

</html>