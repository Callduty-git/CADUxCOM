<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
</head>
<body>
    <div class="register-form" style="max-width: 600px; margin: auto; margin-top: 50px;">
        <h2 style="text-align:center; color:#AA5FC7; margin-bottom: 20px;">Editar Perfil de Empresa</h2>

        @if(session('success'))
            <p style="color: green; text-align:center;">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p style="color: red; text-align:center;">{{ session('error') }}</p>
        @endif
        @if($errors->any())
            <ul style="color: red;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('empresa.perfil.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>Nombre de la Empresa:
                <input type="text" name="Nombre" value="{{ old('Nombre', $empresa->Nombre) }}" required>
            </label>

            <label>Email:
                <input type="email" name="email" value="{{ old('email', $empresa->email) }}" required>
            </label>

            <label>Dirección:
                <input type="text" name="Direccion" value="{{ old('Direccion', $empresa->Direccion) }}">
            </label>

            <label>Municipio:
                <input type="text" name="Municipio" value="{{ old('Municipio', $empresa->Municipio) }}">
            </label>

            <label>Ubicación:
                <input type="text" name="Ubicacion" value="{{ old('Ubicacion', $empresa->Ubicacion) }}">
            </label>

            <label>Teléfono / Contacto:
                <input type="text" name="Contacto" value="{{ old('Contacto', $empresa->Contacto) }}">
            </label>

            <label>NIT:
                <input type="text" name="NIT" value="{{ old('NIT', $empresa->NIT) }}">
            </label>

            <label>Foto de la Empresa:
                <input type="file" name="Foto" accept="image/*">
                @if($empresa->Foto)
                    <p>Actual: <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="Foto empresa" width="100"></p>
                @endif
            </label>

            <label>Certificado Cámara de Comercio (PDF):
                <input type="file" name="Certificado_Camara_de_comercio" accept="application/pdf">
                @if($empresa->Certificado_Camara_de_comercio)
                    <p>Actual: <a href="{{ asset('storage/' . $empresa->Certificado_Camara_de_comercio) }}" target="_blank">Ver certificado</a></p>
                @endif
            </label>

            <div class="button-container">
                <button class="btn-register" type="submit">Guardar cambios</button>
            </div>

            <div style="margin-top: 20px;">
                <a href="{{ route('empresa.productos.index') }}" class="btn-register" style="background-color: #AA5FC7;">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>
