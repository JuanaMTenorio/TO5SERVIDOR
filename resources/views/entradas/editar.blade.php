<!DOCTYPE html>
<html>

<head>
    <title>Editar entrada</title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
</head>

<body>
    <div class="contenedor">
        <h2>Editar entrada</h2>
        <form action="{{ url('/entradas/actualizar/' . $entrada['id']) }}" method="POST">
            @csrf
            <input type="text" name="titulo" value="{{ $entrada['titulo'] }}">
            <input type="text" name="imagen" value="{{ $entrada['imagen'] }}">
            <textarea name="descripcion">{{ $entrada['descripcion'] }}</textarea>
            <input type="date" name="fecha" value="{{ date('Y-m-d', strtotime($entrada['fecha'])) }}">

            <select name="categoria_id">
                @foreach($categorias as $categoria)
                <option value="{{ $categoria['id'] }}"
                    {{ $categoria['id'] == $entrada['categoria_id'] ? 'selected' : '' }}>
                    {{ $categoria['nombre'] }}
                </option>
                @endforeach
            </select>
            <p></p>
            <button type="submit">Actualizar</button>
        </form>
        <br>
        <a href="{{ url('/panel') }}" class="boton">Volver al panel</a>

    </div>
</body>

</html>