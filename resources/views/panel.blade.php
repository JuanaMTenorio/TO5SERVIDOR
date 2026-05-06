<!DOCTYPE html>
<html>

<head>
    <title>Panel de administración</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>
    <div class="contenedor">

        <h2>Bienvenida {{ session('usuario_nombre') }}</h2>
        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif
        <p>Has accedido a una zona privada</p>

        <div class="botonera">
            <div class="fila-botones">
                <a href="{{ url('/registro') }}" class="boton">Crear usuario</a> 
                <a href="{{ url('/categorias/crear') }}" class="boton">Crear categoría</a>
                <a href="{{ url('/entradas/crear') }}" class="boton">Crear entrada</a>
                <a href="{{ url('/logout') }}" class="boton boton-rojo">Cerrar sesión</a>
            </div>
        </div>
        <hr>
        <h3>Listado de entradas</h3>
        <p>Más adelante aquí mostraremos las entradas del blog.</p>

    </div>

</body>

</html>