<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Empresa</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <style>
        .header {
            border-bottom: 3px solid #006400;
        }
        /* ====== MODAL ESTILOS ====== */
        .modal { display: none; position: fixed; z-index: 2000; padding-top: 60px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);}
        .modal-content { background-color: #fff; margin: auto; padding: 20px; border-radius: 10px; width: 500px; max-width: 90%; box-shadow: 0px 4px 8px rgba(0,0,0,0.3);}
        .close { color: #aaa; float: right; font-size: 24px; font-weight: bold; cursor: pointer;}
        .modal-content label { display: block; margin-top: 10px; font-weight: bold;}
        .modal-content input { width: 100%; padding: 8px; margin-top: 4px; border-radius: 6px; border: 1px solid #ccc;}
        .save-btn { margin-top: 15px; padding: 10px 15px; background-color: purple; color: white; border: none; border-radius: 6px; cursor: pointer;}
        /* ====== ESTILOS MODAL BIENVENIDA ====== */
        .modal-bienvenida {
            display: none; /* Oculto por defecto */
            position: fixed;
            z-index: 3000; /* Z-index alto para estar encima de otros modales */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }
        .modal-visible {
            display: flex; /* Muestra el modal */
        }
        .modal-contenido-bienvenida {
            background-color: #333;
            color: #fff;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            border: 2px solid #8B4513; /* Borde estilo madera */
            position: relative;
            font-family: 'Inter', sans-serif;
        }
        .header-modal-bienvenida {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .header-modal-bienvenida .logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }
        .title-modal {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .body-modal-bienvenida h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 10px 0;
        }
        .body-modal-bienvenida p {
            font-size: 1rem;
            font-weight: 400;
        }
    </style>
</head>
<body>
    
    <!-- NUEVO HEADER -->
    <x-header-empresa />

    <div class="main-container">
        <aside class="sidebar">
            <nav class="nav-buttons">
                <a href="{{ route('empresa.dashboard') }}" class="btn">Inicio</a>
                <a href="{{ route('empresa.productos.index') }}" class="btn">Productos</a>
                <a href="{{ route('empresa.facturas') }}" class="btn">Facturas</a>
                <form method="POST" action="{{ route('empresa.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="btn" aria-label="Cerrar sesión">Salir</button>
                </form>
            </nav>
        </aside>

        <main class="dashboard-panel">


            <div class="info-panel">
                <div class="column-left">
                    <div class="info-box"><strong>Nombre:</strong> {{ $empresa->Nombre }}</div>
                    <div class="info-box"><strong>Correo:</strong> {{ $empresa->email }}</div>
                    <div class="info-box"><strong>Dirección:</strong> {{ $empresa->Direccion }}</div>
                    <div class="info-box"><strong>Teléfono:</strong> {{ $empresa->Contacto }}</div>
                    <div class="info-box"><strong>NIT:</strong> {{ $empresa->NIT }}</div>
                    <div class="info-box"><strong>Ubicación:</strong> {{ $empresa->Ubicacion }}</div>
                    <div class="info-box"><strong>Municipio:</strong> {{ $empresa->Municipio }}</div>
                    <div class="info-box"><strong>Fecha de registro:</strong> {{ $empresa->created_at->format('d/m/Y') }}</div>
                    <div class="info-box">
                        <a href="{{ route('empresa.password.change') }}" class="btn small-btn">Cambiar contraseña</a>
                        <button id="openModal" class="btn small-btn" style="margin-left:5px;">Editar perfil</button>
                    </div>
                </div>

                <div class="column-right">
                    <div class="icon-box">
                        @if($empresa->Foto)
                            <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="Foto" class="icon">
                        @else
                            <em>Sin foto</em>
                        @endif
                    </div>
                    <div class="icon-box">
                        @if($empresa->Certificado_Camara_de_comercio)
                            @php
                                $certPath = asset('storage/' . $empresa->Certificado_Camara_de_comercio);
                                $ext = strtolower(pathinfo($empresa->Certificado_Camara_de_comercio, PATHINFO_EXTENSION));
                            @endphp
                            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                <img src="{{ $certPath }}" alt="Certificado" class="icon">
                            @else
                                <a href="{{ $certPath }}" target="_blank" class="btn small-btn">Ver Certificado</a>
                            @endif
                        @else
                            <em>No cargado</em>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h3>Editar Perfil</h3>
            <form id="editProfileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label>Nombre</label>
                <input type="text" name="Nombre" value="{{ $empresa->Nombre }}">
                <label>Correo</label>
                <input type="email" name="email" value="{{ $empresa->email }}">
                <label>Dirección</label>
                <input type="text" name="Direccion" value="{{ $empresa->Direccion }}">
                <label>Teléfono</label>
                <input type="text" name="Contacto" value="{{ $empresa->Contacto }}">
                <label>NIT</label>
                <input type="text" name="NIT" value="{{ $empresa->NIT }}">
                <label>Ubicación</label>
                <input type="text" name="Ubicacion" value="{{ $empresa->Ubicacion }}">
                <label>Municipio</label>
                <input type="text" name="Municipio" value="{{ $empresa->Municipio }}">
                <label>Foto</label>
                <input type="file" name="Foto">
                <label>Certificado Cámara de Comercio</label>
                <input type="file" name="Certificado_Camara_de_comercio">
                <button type="submit" class="save-btn">Guardar cambios</button>
            </form>
        </div>
    </div>
    
    <div id="modal-bienvenida" class="modal-bienvenida">
        <div class="modal-contenido-bienvenida">
            <div class="header-modal-bienvenida">
                <img src="{{ asset('images/logo-caduxcom.png') }}" alt="Logo" class="logo">
                <h2 class="title-modal">CADUxCOM</h2>
            </div>
            <div class="body-modal-bienvenida">
                <h3 id="welcome-message">¡Bienvenida, [nombre de la empresa]!</h3>
                <p>Nos alegra tenerte de nuevo.</p>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Código para el modal de editar perfil
        document.getElementById('openModal').addEventListener('click', function(){
            document.getElementById('editModal').style.display = 'block';
        });
        document.getElementById('closeModal').addEventListener('click', function(){
            document.getElementById('editModal').style.display = 'none';
        });
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = 'none';
            }
        };
        document.getElementById('editProfileForm').addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);
            fetch("{{ route('empresa.perfil.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error("Error en la actualización");
                return response.json();
            })
            .then(data => {
                alert("Perfil actualizado correctamente ✅");
                location.reload();
            })
            .catch(error => {
                console.error(error);
                alert("Hubo un problema al actualizar el perfil ❌");
            });
        });

        // Código NUEVO para el modal de bienvenida
        const urlParams = new URLSearchParams(window.location.search);
        const showWelcomeModal = urlParams.get('welcome');

        if (showWelcomeModal === 'true') {
            const modal = document.getElementById('modal-bienvenida');
            const welcomeMessage = document.getElementById('welcome-message');
            const empresaNombre = "{{ $empresa->Nombre }}";
            
            // Actualiza el mensaje con el nombre de la empresa
            welcomeMessage.textContent = `¡Bienvenida, ${empresaNombre}!`;

            // Muestra el modal
            modal.classList.add('modal-visible');

            // Oculta el modal después de 4 segundos
            setTimeout(function() {
                modal.classList.remove('modal-visible');
            }, 4000); // 4000 milisegundos = 4 segundos
        }
    });
    </script>
</body>
</html>