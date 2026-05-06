<!DOCTYPE html>
<html>
<head>
    <title>Editar categoría</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>

<div class="contenedor">

    <h2>Editar categoría</h2>

    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <form action="{{ url('/categorias/actualizar/' . $categoria['id']) }}" method="POST">
        @csrf

        <input type="text" name="nombre" value="{{ $categoria['nombre'] }}">

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="{{ url('/categorias') }}" class="boton">Volver</a>

</div>

</body>
</html>