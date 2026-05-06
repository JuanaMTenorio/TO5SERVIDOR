<!DOCTYPE html>
<html>
<head>
    <title>Crear categoría</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>
<div class="contenedor">
    <h2>Crear categoría</h2>
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif
    <form action="{{ url('/categorias/guardar') }}" method="POST">
        @csrf

        <input type="text" name="nombre" placeholder="Nombre de la categoría">

        <button type="submit">Guardar categoría</button>
    </form>
</div>
</body>
</html>