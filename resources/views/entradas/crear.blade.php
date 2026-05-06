<!DOCTYPE html>
<html>
<head>
    <title>Crear entrada</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>
<body>
    <div class="contenedor">
        <h2>Crear entrada</h2>
        @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
        @endif
        <form action="{{ url('/entradas/guardar') }}" method="POST">
            @csrf
            <input type="text" name="titulo" placeholder="Título de la entrada">
            <input type="text" name="imagen" placeholder="Nombre de la imagen">
            <textarea name="descripcion" placeholder="Descripción de la entrada"></textarea>
            <input type="date" name="fecha">
            <select name="categoria_id">
                <option value="">Selecciona una categoría</option>

                @foreach($categorias as $categoria)
                <option value="{{ $categoria['id'] }}">
                    {{ $categoria['nombre'] }}
                </option>
                @endforeach
            </select>
            <p></p>
            <button type="submit">Guardar entrada</button>
        </form>
        <br>
        <a href="{{ url('/panel') }}" class="boton">Volver al panel</a>
    </div>
</body>
</html>