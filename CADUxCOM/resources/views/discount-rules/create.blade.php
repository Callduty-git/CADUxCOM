<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Regla de Descuento - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/discount-rules.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-header-pages />
    
    <div class="discount-rules-container">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">Nueva Regla de Descuento</h1>
                <p class="page-subtitle">Configura una nueva regla de descuento progresivo basada en la proximidad a la fecha de caducidad</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('discount-rules.index') }}" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="form-container">
            <form action="{{ route('discount-rules.store') }}" method="POST" class="discount-form">
                @csrf
                
                <!-- Información básica -->
                <div class="form-section">
                    <h2 class="section-title">Información Básica</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name" class="form-label">Nombre de la regla *</label>
                            <input type="text" id="name" name="name" class="form-input" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea id="description" name="description" class="form-textarea" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuración de descuento -->
                <div class="form-section">
                    <h2 class="section-title">Configuración de Descuento</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="days_before_expiry" class="form-label">Días antes de caducidad *</label>
                            <input type="number" id="days_before_expiry" name="days_before_expiry" 
                                   class="form-input" value="{{ old('days_before_expiry') }}" 
                                   min="1" max="30" required>
                            <small class="form-help">Número de días antes de la caducidad para aplicar el descuento</small>
                            @error('days_before_expiry')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="discount_type" class="form-label">Tipo de descuento *</label>
                            <select id="discount_type" name="discount_type" class="form-select" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                                    Porcentaje (%)
                                </option>
                                <option value="fixed_amount" {{ old('discount_type') === 'fixed_amount' ? 'selected' : '' }}>
                                    Cantidad fija ($)
                                </option>
                            </select>
                            @error('discount_type')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="discount_value" class="form-label">Valor del descuento *</label>
                            <input type="number" id="discount_value" name="discount_value" 
                                   class="form-input" value="{{ old('discount_value') }}" 
                                   step="0.01" min="0.01" required>
                            <small class="form-help" id="discount_help">
                                Ingresa el valor del descuento según el tipo seleccionado
                            </small>
                            @error('discount_value')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="minimum_product_price" class="form-label">Precio mínimo del producto</label>
                            <input type="number" id="minimum_product_price" name="minimum_product_price" 
                                   class="form-input" value="{{ old('minimum_product_price', 0) }}" 
                                   step="0.01" min="0">
                            <small class="form-help">Precio mínimo que debe tener el producto para aplicar el descuento</small>
                            @error('minimum_product_price')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Límites de descuento -->
                <div class="form-section">
                    <h2 class="section-title">Límites de Descuento</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="minimum_discount" class="form-label">Descuento mínimo</label>
                            <input type="number" id="minimum_discount" name="minimum_discount" 
                                   class="form-input" value="{{ old('minimum_discount', 0) }}" 
                                   step="0.01" min="0">
                            <small class="form-help">Descuento mínimo garantizado</small>
                            @error('minimum_discount')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="maximum_discount" class="form-label">Descuento máximo</label>
                            <input type="number" id="maximum_discount" name="maximum_discount" 
                                   class="form-input" value="{{ old('maximum_discount') }}" 
                                   step="0.01" min="0">
                            <small class="form-help">Descuento máximo permitido</small>
                            @error('maximum_discount')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Aplicabilidad -->
                <div class="form-section">
                    <h2 class="section-title">Aplicabilidad</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Categorías aplicables</label>
                            <div class="checkbox-group">
                                @foreach($categorias as $categoria)
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="category_{{ $categoria->Id_Categoria }}" 
                                               name="applicable_categories[]" value="{{ $categoria->Id_Categoria }}"
                                               {{ in_array($categoria->Id_Categoria, old('applicable_categories', [])) ? 'checked' : '' }}>
                                        <label for="category_{{ $categoria->Id_Categoria }}" class="checkbox-label">
                                            {{ $categoria->Nombre }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-help">Deja vacío para aplicar a todas las categorías</small>
                            @error('applicable_categories')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Productos específicos</label>
                            <div class="product-selector">
                                <select id="product_selector" class="form-select">
                                    <option value="">Seleccionar producto</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->Id_Producto }}" 
                                                data-name="{{ $producto->Nombre }} - {{ $producto->Marca }}">
                                            {{ $producto->Nombre }} - {{ $producto->Marca }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" id="add_product" class="btn btn-sm btn-primary">Agregar</button>
                            </div>
                            <div id="selected_products" class="selected-products">
                                @foreach(old('applicable_products', []) as $productId)
                                    @php
                                        $producto = $productos->firstWhere('Id_Producto', $productId);
                                    @endphp
                                    @if($producto)
                                        <div class="selected-product">
                                            <span>{{ $producto->Nombre }} - {{ $producto->Marca }}</span>
                                            <button type="button" class="remove-product" data-product-id="{{ $productId }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                            <input type="hidden" name="applicable_products[]" value="{{ $productId }}">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <small class="form-help">Selecciona productos específicos para aplicar el descuento</small>
                            @error('applicable_products')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuración adicional -->
                <div class="form-section">
                    <h2 class="section-title">Configuración Adicional</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Configuración automática</label>
                            <div class="checkbox-item">
                                <input type="checkbox" id="is_automatic" name="is_automatic" 
                                       {{ old('is_automatic', true) ? 'checked' : '' }}>
                                <label for="is_automatic" class="checkbox-label">
                                    Aplicar descuento automáticamente
                                </label>
                            </div>
                            <small class="form-help">Si está marcado, el descuento se aplicará automáticamente a los productos que cumplan los criterios</small>
                        </div>

                        <div class="form-group">
                            <label for="starts_at" class="form-label">Fecha de inicio</label>
                            <input type="datetime-local" id="starts_at" name="starts_at" 
                                   class="form-input" value="{{ old('starts_at') }}">
                            <small class="form-help">Fecha y hora de inicio de la regla (opcional)</small>
                            @error('starts_at')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="expires_at" class="form-label">Fecha de expiración</label>
                            <input type="datetime-local" id="expires_at" name="expires_at" 
                                   class="form-input" value="{{ old('expires_at') }}">
                            <small class="form-help">Fecha y hora de expiración de la regla (opcional)</small>
                            @error('expires_at')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="form-actions">
                    <a href="{{ route('discount-rules.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Crear Regla
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />

    <script>
        // Actualizar ayuda del descuento según el tipo
        document.getElementById('discount_type').addEventListener('change', function() {
            const help = document.getElementById('discount_help');
            const value = document.getElementById('discount_value');
            
            if (this.value === 'percentage') {
                help.textContent = 'Ingresa el porcentaje de descuento (ej: 10 para 10%)';
                value.max = 100;
            } else if (this.value === 'fixed_amount') {
                help.textContent = 'Ingresa la cantidad fija de descuento en pesos (ej: 1000)';
                value.removeAttribute('max');
            }
        });

        // Gestión de productos seleccionados
        document.getElementById('add_product').addEventListener('click', function() {
            const selector = document.getElementById('product_selector');
            const selectedOption = selector.options[selector.selectedIndex];
            
            if (selectedOption.value) {
                const productId = selectedOption.value;
                const productName = selectedOption.dataset.name;
                
                // Verificar si ya está seleccionado
                if (document.querySelector(`input[name="applicable_products[]"][value="${productId}"]`)) {
                    alert('Este producto ya está seleccionado');
                    return;
                }
                
                // Crear elemento del producto seleccionado
                const selectedProducts = document.getElementById('selected_products');
                const productDiv = document.createElement('div');
                productDiv.className = 'selected-product';
                productDiv.innerHTML = `
                    <span>${productName}</span>
                    <button type="button" class="remove-product" data-product-id="${productId}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <input type="hidden" name="applicable_products[]" value="${productId}">
                `;
                
                selectedProducts.appendChild(productDiv);
                selector.selectedIndex = 0;
            }
        });

        // Remover productos seleccionados
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                e.target.closest('.selected-product').remove();
            }
        });

        // Mostrar errores de validación
        @if($errors->any())
            showNotification('Por favor corrige los errores en el formulario', 'error');
        @endif

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
