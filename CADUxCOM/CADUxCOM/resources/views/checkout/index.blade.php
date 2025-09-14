@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finalizar Compra</h1>
            <p class="text-gray-600">Completa tu información para procesar el pedido</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulario de checkout -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Información del cliente -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Cliente</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre Completo *
                                </label>
                                <input type="text" id="customer_name" name="customer_name" required
                                       value="{{ old('customer_name', $userData['name'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('customer_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email *
                                </label>
                                <input type="email" id="customer_email" name="customer_email" required
                                       value="{{ old('customer_email', $userData['email'] ?? '') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('customer_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Teléfono
                                </label>
                                <input type="tel" id="customer_phone" name="customer_phone"
                                       value="{{ old('customer_phone') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('customer_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dirección de envío -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Dirección de Envío</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Dirección *
                                </label>
                                <textarea id="shipping_address" name="shipping_address" required rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="shipping_city" class="block text-sm font-medium text-gray-700 mb-1">
                                        Ciudad *
                                    </label>
                                    <input type="text" id="shipping_city" name="shipping_city" required
                                           value="{{ old('shipping_city') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('shipping_city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="shipping_state" class="block text-sm font-medium text-gray-700 mb-1">
                                        Departamento *
                                    </label>
                                    <input type="text" id="shipping_state" name="shipping_state" required
                                           value="{{ old('shipping_state') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('shipping_state')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                        Código Postal *
                                    </label>
                                    <input type="text" id="shipping_postal_code" name="shipping_postal_code" required
                                           value="{{ old('shipping_postal_code') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('shipping_postal_code')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="shipping_country" class="block text-sm font-medium text-gray-700 mb-1">
                                    País *
                                </label>
                                <select id="shipping_country" name="shipping_country" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Colombia" {{ old('shipping_country', 'Colombia') == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                                    <option value="Venezuela" {{ old('shipping_country') == 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
                                    <option value="Ecuador" {{ old('shipping_country') == 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                                    <option value="Perú" {{ old('shipping_country') == 'Perú' ? 'selected' : '' }}>Perú</option>
                                </select>
                                @error('shipping_country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dirección de facturación -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Dirección de Facturación</h2>
                            <label class="flex items-center">
                                <input type="checkbox" id="same_as_shipping" name="same_as_shipping" 
                                       {{ old('same_as_shipping') ? 'checked' : '' }}
                                       class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Igual que la dirección de envío</span>
                            </label>
                        </div>
                        
                        <div id="billing_fields" class="space-y-4">
                            <div>
                                <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Dirección
                                </label>
                                <textarea id="billing_address" name="billing_address" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('billing_address') }}</textarea>
                                @error('billing_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">
                                        Ciudad
                                    </label>
                                    <input type="text" id="billing_city" name="billing_city"
                                           value="{{ old('billing_city') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('billing_city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="billing_state" class="block text-sm font-medium text-gray-700 mb-1">
                                        Departamento
                                    </label>
                                    <input type="text" id="billing_state" name="billing_state"
                                           value="{{ old('billing_state') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('billing_state')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="billing_postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                        Código Postal
                                    </label>
                                    <input type="text" id="billing_postal_code" name="billing_postal_code"
                                           value="{{ old('billing_postal_code') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('billing_postal_code')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="billing_country" class="block text-sm font-medium text-gray-700 mb-1">
                                    País
                                </label>
                                <select id="billing_country" name="billing_country"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="Colombia" {{ old('billing_country', 'Colombia') == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                                    <option value="Venezuela" {{ old('billing_country') == 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
                                    <option value="Ecuador" {{ old('billing_country') == 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                                    <option value="Perú" {{ old('billing_country') == 'Perú' ? 'selected' : '' }}>Perú</option>
                                </select>
                                @error('billing_country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Método de pago -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Método de Pago</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="credit_card" 
                                       {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }}
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="font-medium">Tarjeta de Crédito</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="debit_card" 
                                       {{ old('payment_method') == 'debit_card' ? 'checked' : '' }}
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="font-medium">Tarjeta Débito</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank_transfer" 
                                       {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    <span class="font-medium">Transferencia Bancaria</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cash_on_delivery" 
                                       {{ old('payment_method') == 'cash_on_delivery' ? 'checked' : '' }}
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="font-medium">Pago Contra Entrega</span>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="digital_wallet" 
                                       {{ old('payment_method') == 'digital_wallet' ? 'checked' : '' }}
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                    <span class="font-medium">Billetera Digital</span>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notas adicionales -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Notas Adicionales</h2>
                        <textarea id="notes" name="notes" rows="3" placeholder="Instrucciones especiales para la entrega..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Resumen del pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-4">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Pedido</h2>
                            
                            <!-- Productos -->
                            <div class="space-y-3 mb-6">
                                @foreach($items as $item)
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $item['product']->Foto ? asset('storage/' . $item['product']->Foto) : asset('images/default-product.png') }}" 
                                             alt="{{ $item['product']->Nombre }}" 
                                             class="w-12 h-12 object-cover rounded">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $item['product']->Nombre }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Cantidad: {{ $item['quantity'] }}
                                            </p>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            ${{ number_format($item['line_total'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Totales -->
                            <div class="space-y-2 border-t pt-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium">${{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">IVA (19%)</span>
                                    <span class="font-medium">${{ number_format($tax, 0, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Envío</span>
                                    <span class="font-medium">
                                        @if($shipping > 0)
                                            ${{ number_format($shipping, 0, ',', '.') }}
                                        @else
                                            <span class="text-green-600">Gratis</span>
                                        @endif
                                    </span>
                                </div>
                                
                                @if($couponDiscount > 0)
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>Descuento</span>
                                        <span class="font-medium">-${{ number_format($couponDiscount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                
                                <hr class="my-3">
                                
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total</span>
                                    <span class="text-blue-600">${{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <!-- Botón de procesar pedido -->
                            <button type="submit" id="submitBtn"
                                    class="w-full mt-6 bg-blue-600 text-white py-3 px-4 rounded-md font-medium hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="submitText">Procesar Pedido</span>
                                <span id="submitLoading" class="hidden">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                            
                            <!-- Términos y condiciones -->
                            <p class="text-xs text-gray-500 mt-4 text-center">
                                Al procesar este pedido, aceptas nuestros 
                                <a href="#" class="text-blue-600 hover:underline">términos y condiciones</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
    const billingFields = document.getElementById('billing_fields');
    const billingInputs = billingFields.querySelectorAll('input, textarea, select');
    
    // Manejar checkbox "igual que envío"
    sameAsShippingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            billingFields.style.display = 'none';
            billingInputs.forEach(input => input.disabled = true);
        } else {
            billingFields.style.display = 'block';
            billingInputs.forEach(input => input.disabled = false);
        }
    });
    
    // Inicializar estado
    if (sameAsShippingCheckbox.checked) {
        billingFields.style.display = 'none';
        billingInputs.forEach(input => input.disabled = true);
    }
    
    // Manejar envío del formulario
    const form = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
    });
});
</script>
@endsection

