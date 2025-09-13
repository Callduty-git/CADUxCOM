<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Producto - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
        }
        
        /* ====== SIDEBAR CONTAINER ====== */
        .sidebar-container {
            position: fixed;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 450px;
            height: 80vh;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1001;
            transition: all 0.3s ease;
            opacity: 0.95;
        }
        
        .sidebar:hover {
            opacity: 1;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: scale(1.02);
        }
        
        .sidebar-container:hover {
            transform: translateY(-50%) scale(1.02);
        }
        
        .dashboard-panel {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .create-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 0;
            overflow: hidden;
            min-height: calc(100vh - 200px);
        }
        
        .create-header {
            background: linear-gradient(135deg, #AA5FC7 0%, #8B4A9F 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .create-title {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .create-icon {
            font-size: 2rem;
        }
        
        .create-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .create-content {
            padding: 40px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }
        
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            resize: vertical;
            min-height: 100px;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }
        
        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }
        
        .form-select:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
        }
        
        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload input[type=file] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px;
            border: 2px dashed #28a745;
            border-radius: 10px;
            background: rgba(40, 167, 69, 0.05);
            color: #28a745;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background: rgba(40, 167, 69, 0.1);
            border-color: #20c997;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #AA5FC7 0%, #8B4A9F 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(170, 95, 199, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
        }
        
        .image-preview {
            width: 100%;
            max-width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            margin-top: 15px;
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .create-content {
                padding: 20px;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <!-- HEADER -->
        <x-header-empresa />

    <div class="sidebar-container">
        <aside class="sidebar" id="sidebar">
            <nav class="nav-buttons">
                <a href="{{ route('empresa.dashboard') }}" class="btn">Inicio</a>
                <a href="{{ route('empresa.productos.index') }}" class="btn">Productos</a>
                <a href="{{ route('empresa.facturas') }}" class="btn">Log de Productos</a>
                <form method="POST" action="{{ route('empresa.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="btn">Salir</button>
                </form>
            </nav>
        </aside>
    </div>

    <div class="main-container">
        <main class="dashboard-panel">
            <div class="create-container">
                <div class="create-header">
                    <div>
                        <div class="create-title">
                            <i class="fas fa-plus-circle create-icon"></i>
                            Crear Nuevo Producto
                        </div>
                        <div class="create-subtitle">Completa la información para agregar un nuevo producto a tu catálogo</div>
                    </div>
                </div>
                
                <div class="create-content">
                    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        <div class="form-grid">
                            <!-- Información Básica -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle"></i>
                                    Información Básica
                                </h3>
                                
                                <div class="form-group">
                                    <label for="Nombre" class="form-label">Nombre del Producto</label>
                                    <input type="text" id="Nombre" name="Nombre" class="form-input" value="{{ old('Nombre') }}" required placeholder="Ej: Leche entera 1L">
                                    @error('Nombre')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Marca" class="form-label">Marca</label>
                                    <input type="text" id="Marca" name="Marca" class="form-input" value="{{ old('Marca') }}" required placeholder="Ej: Alquería">
                                    @error('Marca')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Codigo" class="form-label">Código del Producto</label>
                                    <input type="text" id="Codigo" name="Codigo" class="form-input" value="{{ old('Codigo') }}" required placeholder="Ej: LEC001">
                                    @error('Codigo')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Tipo" class="form-label">Tipo/Unidad</label>
                                    <input type="text" id="Tipo" name="Tipo" class="form-input" value="{{ old('Tipo') }}" required placeholder="Ej: Litro, Kilo, Unidad">
                                    @error('Tipo')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Descripcion" class="form-label">Descripción</label>
                                    <textarea id="Descripcion" name="Descripcion" class="form-textarea" placeholder="Describe las características del producto...">{{ old('Descripcion') }}</textarea>
                                    @error('Descripcion')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Precios y Stock -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-dollar-sign"></i>
                                    Precios y Stock
                                </h3>
                                
                                <div class="form-group">
                                    <label for="PrecioOriginal" class="form-label">Precio Original</label>
                                    <input type="number" id="PrecioOriginal" name="PrecioOriginal" class="form-input" value="{{ old('PrecioOriginal') }}" step="0.01" required min="0" placeholder="0.00">
                                    @error('PrecioOriginal')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Precio" class="form-label">Precio de Venta</label>
                                    <input type="number" id="Precio" name="Precio" class="form-input" value="{{ old('Precio') }}" step="0.01" required min="0" placeholder="0.00">
                                    @error('Precio')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Cantidad" class="form-label">Cantidad en Stock</label>
                                    <input type="number" id="Cantidad" name="Cantidad" class="form-input" value="{{ old('Cantidad') }}" required min="0" placeholder="0">
                                    @error('Cantidad')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Fecha_Caducidad" class="form-label">Fecha de Caducidad</label>
                                    <input type="date" id="Fecha_Caducidad" name="Fecha_Caducidad" class="form-input" value="{{ old('Fecha_Caducidad') }}">
                                    @error('Fecha_Caducidad')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Categorización -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-tags"></i>
                                Categorización
                            </h3>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="Id_Empresa" class="form-label">Empresa</label>
                                    <select id="Id_Empresa" name="Id_Empresa" class="form-select" required>
                                        <option value="">Seleccione una empresa</option>
                                        @foreach ($empresas as $empresa)
                                            <option value="{{ $empresa->Id_Empresa }}" {{ old('Id_Empresa') == $empresa->Id_Empresa ? 'selected' : '' }}>
                                                {{ $empresa->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Id_Empresa')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Id_Subcategoria" class="form-label">Subcategoría</label>
                                    <select id="Id_Subcategoria" name="Id_Subcategoria" class="form-select" required>
                                        <option value="">Seleccione una subcategoría</option>
                                        @foreach ($subcategorias as $subcategoria)
                                            <option value="{{ $subcategoria->Id_Subcategoria }}" {{ old('Id_Subcategoria') == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                                                {{ $subcategoria->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Id_Subcategoria')
                                        <div class="error-message">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Imagen del Producto -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-image"></i>
                                Imagen del Producto
                            </h3>
                            
                            <div class="form-group">
                                <div class="file-upload">
                                    <input type="file" id="Foto" name="Foto" accept="image/*" onchange="previewImage(this)">
                                    <label for="Foto" class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        Seleccionar imagen del producto
                                    </label>
                                </div>
                                <div id="imagePreview" style="display: none;">
                                    <img id="previewImg" class="image-preview" alt="Vista previa">
                                </div>
                                @error('Foto')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="form-actions">
                            <a href="{{ route('empresa.productos.index') }}" class="btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i>
                                Crear Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Funcionalidad del sidebar deslizable
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            let sidebarTimeout;
            
            // Mostrar sidebar al hacer hover en el área izquierda
            document.addEventListener('mousemove', function(e) {
                if (e.clientX <= 20) { // Área de 20px desde el borde izquierdo
                    clearTimeout(sidebarTimeout);
                    sidebar.style.left = '0';
                }
            });
            
            // Ocultar sidebar cuando el mouse sale del área
            sidebar.addEventListener('mouseleave', function() {
                sidebarTimeout = setTimeout(function() {
                    sidebar.style.left = '-250px';
                }, 300); // Delay de 300ms antes de ocultar
            });
            
            // Cancelar ocultar si el mouse vuelve al sidebar
            sidebar.addEventListener('mouseenter', function() {
                clearTimeout(sidebarTimeout);
            });
        });
    </script>
    </div>

    <!-- Footer -->
    <x-footer />
</body>
</html>