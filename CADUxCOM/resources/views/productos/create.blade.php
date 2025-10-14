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
    <link rel="stylesheet" href="{{ asset('css/empresa-producto-create.css') }}">
</head>
<body>
    <div class="main-content">
        <!-- HEADER -->
        <x-header-empresa />


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
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Marca" class="form-label">Marca</label>
                                    <input type="text" id="Marca" name="Marca" class="form-input" value="{{ old('Marca') }}" required placeholder="Ej: Alquería">
                                    @error('Marca')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Codigo" class="form-label">Código del Producto</label>
                                    <input type="text" id="Codigo" name="Codigo" class="form-input" value="{{ old('Codigo') }}" required placeholder="Ej: LEC001">
                                    @error('Codigo')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Tipo" class="form-label">Tipo/Unidad</label>
                                    <input type="text" id="Tipo" name="Tipo" class="form-input" value="{{ old('Tipo') }}" required placeholder="Ej: Litro, Kilo, Unidad">
                                    @error('Tipo')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Descripcion" class="form-label">Descripción</label>
                                    <textarea id="Descripcion" name="Descripcion" class="form-textarea" placeholder="Describe las características del producto...">{{ old('Descripcion') }}</textarea>
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
                                    <input type="number" id="PrecioOriginal" name="PrecioOriginal" class="form-input" value="{{ old('PrecioOriginal') }}" step="0.01" required min="0" placeholder="0.00">
                                    @error('PrecioOriginal')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Precio" class="form-label">Precio de Venta</label>
                                    <input type="number" id="Precio" name="Precio" class="form-input" value="{{ old('Precio') }}" step="0.01" required min="0" placeholder="0.00">
                                    @error('Precio')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Cantidad" class="form-label">Cantidad en Stock</label>
                                    <input type="number" id="Cantidad" name="Cantidad" class="form-input" value="{{ old('Cantidad') }}" required min="0" placeholder="0">
                                    @error('Cantidad')
                                        <div class="form-error-message">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="Fecha_Caducidad" class="form-label">Fecha de Caducidad</label>
                                    <input type="date" id="Fecha_Caducidad" name="Fecha_Caducidad" class="form-input" value="{{ old('Fecha_Caducidad') }}">
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
                                            <option value="{{ $empresa->Id_Empresa }}" {{ old('Id_Empresa') == $empresa->Id_Empresa ? 'selected' : '' }}>
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
                                            <option value="{{ $subcategoria->Id_Subcategoria }}" {{ old('Id_Subcategoria') == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
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
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>