<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
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
        
        .edit-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 0;
            overflow: hidden;
            min-height: calc(100vh - 200px);
        }
        
        .edit-header {
            background: linear-gradient(135deg, #AA5FC7 0%, #8B4A9F 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .edit-title {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .edit-icon {
            font-size: 2rem;
        }
        
        .edit-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .edit-content {
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
            border: 2px dashed #ffc107;
            border-radius: 10px;
            background: rgba(255, 193, 7, 0.05);
            color: #ffc107;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-upload-label:hover {
            background: rgba(255, 193, 7, 0.1);
            border-color: #ff8c00;
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
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
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
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
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
        
        .current-image {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            border: 1px solid #e9ecef;
        }
        
        .current-image img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .edit-content {
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
            <!-- Mensajes de sesión -->
            @if(session('success'))
                <div class="session-message success">
                    <div class="notification-icon">✓</div>
                    <div class="notification-content">
                        <div class="notification-message">{{ session('success') }}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="session-message error">
                    <div class="notification-icon">✕</div>
                    <div class="notification-content">
                        <div class="notification-message">{{ session('error') }}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            <div class="edit-container">
                <div class="edit-header">
                    <div>
                        <div class="edit-title">
                            <i class="fas fa-edit edit-icon"></i>
                            Editar Producto
                        </div>
                        <div class="edit-subtitle">Modifica la información del producto: {{ $producto->Nombre }}</div>
                    </div>
                </div>
                
                <div class="edit-content">
                    <form action="{{ route('productos.update', $producto->Id_Producto) }}" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <!-- Información Básica -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle"></i>
                                    Información Básica
                                </h3>
                                
                                <div class="form-group">
                                    <label for="Nombre" class="form-label">Nombre del Producto</label>
                                    <input type="text" id="Nombre" name="Nombre" class="form-input" value="{{ old('Nombre', $producto->Nombre) }}" required placeholder="Ej: Leche entera 1L">
                                    @error('Nombre')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Marca" class="form-label">Marca</label>
                                    <input type="text" id="Marca" name="Marca" class="form-input" value="{{ old('Marca', $producto->Marca) }}" required placeholder="Ej: Alquería">
                                    @error('Marca')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Codigo" class="form-label">Código del Producto</label>
                                    <input type="text" id="Codigo" name="Codigo" class="form-input" value="{{ old('Codigo', $producto->Codigo) }}" required placeholder="Ej: LEC001">
                                    @error('Codigo')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Tipo" class="form-label">Tipo/Unidad</label>
                                    <input type="text" id="Tipo" name="Tipo" class="form-input" value="{{ old('Tipo', $producto->Tipo) }}" required placeholder="Ej: Litro, Kilo, Unidad">
                                    @error('Tipo')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Descripcion" class="form-label">Descripción</label>
                                    <textarea id="Descripcion" name="Descripcion" class="form-textarea" placeholder="Describe las características del producto...">{{ old('Descripcion', $producto->Descripcion) }}</textarea>
                                    @error('Descripcion')
                                        <div class="form-error-message">
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
                                    <input type="number" id="PrecioOriginal" name="PrecioOriginal" class="form-input" value="{{ old('PrecioOriginal', $producto->PrecioOriginal) }}" step="0.01" required min="0" placeholder="0.00">
                                    @error('PrecioOriginal')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Precio" class="form-label">Precio de Venta</label>
                                    <input type="number" id="Precio" name="Precio" class="form-input" value="{{ old('Precio', $producto->Precio) }}" step="0.01" required min="0" placeholder="0.00">
                                    @error('Precio')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Cantidad" class="form-label">Cantidad en Stock</label>
                                    <input type="number" id="Cantidad" name="Cantidad" class="form-input" value="{{ old('Cantidad', $producto->Cantidad) }}" required min="0" placeholder="0">
                                    @error('Cantidad')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Fecha_Caducidad" class="form-label">Fecha de Caducidad</label>
                                    <input type="date" id="Fecha_Caducidad" name="Fecha_Caducidad" class="form-input" value="{{ old('Fecha_Caducidad', $producto->Fecha_Caducidad) }}">
                                    @error('Fecha_Caducidad')
                                        <div class="form-error-message">
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
                                            <option value="{{ $empresa->Id_Empresa }}" {{ $producto->Id_Empresa == $empresa->Id_Empresa ? 'selected' : '' }}>
                                                {{ $empresa->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Id_Empresa')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Id_Subcategoria" class="form-label">Subcategoría</label>
                                    <select id="Id_Subcategoria" name="Id_Subcategoria" class="form-select" required>
                                        <option value="">Seleccione una subcategoría</option>
                                        @foreach ($subcategorias as $subcategoria)
                                            <option value="{{ $subcategoria->Id_Subcategoria }}" {{ $producto->Id_Subcategoria == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                                                {{ $subcategoria->Nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('Id_Subcategoria')
                                        <div class="form-error-message">
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
                            
                            @if ($producto->Foto)
                                <div class="current-image">
                                    <h4 class="form-label">Imagen Actual</h4>
                                    <img src="{{ asset('storage/' . $producto->Foto) }}" alt="Foto actual del producto">
                                </div>
                            @endif
                            
                            <div class="form-group">
                                <div class="file-upload">
                                    <input type="file" id="Foto" name="Foto" accept="image/*" onchange="previewImage(this)">
                                    <label for="Foto" class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        {{ $producto->Foto ? 'Cambiar imagen del producto' : 'Seleccionar imagen del producto' }}
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
                                Guardar Cambios
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
    </script>
    </div>

    <!-- Footer -->
    <x-footer />
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>
    