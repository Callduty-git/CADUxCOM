@extends('layouts.app')

@section('title', 'Detalle de Orden #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        Orden #{{ $order->order_number }}
                    </h1>
                    <p class="text-gray-600">
                        Realizada el {{ $order->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                        @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                        @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $order->getStatusInSpanish() }}
                    </span>
                    <p class="text-2xl font-bold text-gray-900 mt-2">
                        ${{ number_format($order->total_amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Productos -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Productos</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                    <img src="{{ $item->product_image ? asset('storage/' . $item->product_image) : asset('images/default-product.png') }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="w-16 h-16 object-cover rounded">
                                    
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $item->product_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $item->empresa_name }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Código: {{ $item->product_sku }}
                                        </p>
                                        @if($item->product_category)
                                            <p class="text-sm text-gray-500">
                                                Categoría: {{ $item->product_category }}
                                                @if($item->product_subcategory)
                                                    - {{ $item->product_subcategory }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">
                                            Cantidad: {{ $item->quantity }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            ${{ number_format($item->unit_price, 0, ',', '.') }} c/u
                                        </p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            ${{ number_format($item->total_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Información de envío -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Información de Envío</h2>
                    </div>
                    <div class="p-6">
                        <!-- Aviso importante sobre envíos -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Información importante sobre envíos</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>CADUxCOM es una plataforma de conexión. Después de tu compra, recibirás la información de contacto de la empresa vendedora para coordinar directamente el envío de tus productos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Dirección de Envío Registrada</h3>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium">{{ $order->customer_name }}</p>
                                    <p>{{ $order->shipping_address }}</p>
                                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                                    <p>{{ $order->shipping_postal_code }}, {{ $order->shipping_country }}</p>
                                    @if($order->customer_phone)
                                        <p class="mt-2">Tel: {{ $order->customer_phone }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Dirección de Facturación</h3>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium">{{ $order->customer_name }}</p>
                                    <p>{{ $order->billing_address }}</p>
                                    <p>{{ $order->billing_city }}, {{ $order->billing_state }}</p>
                                    <p>{{ $order->billing_postal_code }}, {{ $order->billing_country }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($order->tracking_number)
                            <div class="mt-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <h3 class="text-sm font-medium text-purple-900 mb-2">Información de Seguimiento</h3>
                                <p class="text-sm text-purple-800">
                                    Número de referencia: <span class="font-mono font-medium">{{ $order->tracking_number }}</span>
                                </p>
                                <p class="text-sm text-purple-700 mt-2">
                                    Para seguimiento detallado, contacta directamente con la empresa vendedora usando la información de contacto proporcionada.
                                </p>
                                @if($order->shipped_at)
                                    <p class="text-sm text-purple-800 mt-1">
                                        Procesado el: {{ $order->shipped_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Notas -->
                @if($order->notes || $order->admin_notes)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Notas</h2>
                        </div>
                        <div class="p-6">
                            @if($order->notes)
                                <div class="mb-4">
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">Notas del Cliente</h3>
                                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                                </div>
                            @endif
                            
                            @if($order->admin_notes)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-2">Notas Administrativas</h3>
                                    <p class="text-sm text-gray-600">{{ $order->admin_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Panel lateral -->
            <div class="lg:col-span-1">
                <!-- Resumen de la orden -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Resumen de la Orden</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">IVA (19%)</span>
                                <span class="font-medium">${{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Envío</span>
                                <span class="font-medium text-blue-600">
                                    Coordinado con la empresa
                                </span>
                            </div>
                            
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-sm text-green-600">
                                    <span>Descuento</span>
                                    <span class="font-medium">-${{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            
                            
                            <hr class="my-4">
                            
                            <div class="flex justify-between text-lg font-semibold">
                                <span>Total</span>
                                <span class="text-blue-600">${{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de pago -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Información de Pago</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">Método de pago:</span>
                                <p class="font-medium">{{ $order->getPaymentMethodInSpanish() }}</p>
                            </div>
                            
                            @if($order->payment_reference)
                                <div>
                                    <span class="text-sm text-gray-600">Referencia:</span>
                                    <p class="font-medium font-mono">{{ $order->payment_reference }}</p>
                                </div>
                            @endif
                            
                            @if($order->paid_at)
                                <div>
                                    <span class="text-sm text-gray-600">Fecha de pago:</span>
                                    <p class="font-medium">{{ $order->paid_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Acciones</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="{{ route('orders.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver a Mis Órdenes
                            </a>
                            
                            @if($order->canBeCancelled())
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="event.preventDefault(); showModalAlert({
                                    title: 'Cancelar Orden',
                                    message: '¿Estás seguro de que quieres cancelar esta orden?',
                                    confirmText: 'Sí, cancelar',
                                    cancelText: 'No',
                                    color: '#AA5FC7',
                                    accent: '#89CF6D',
                                    showCancel: true,
                                    onConfirm: () => this.submit(),
                                    onCancel: () => {} });">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancelar Orden
                                    </button>
                                </form>
                            @endif
                            
                            @if($order->canBeRefunded())
                                <form action="{{ route('orders.refund', $order->id) }}" method="POST" onsubmit="event.preventDefault(); showModalAlert({
                                    title: 'Solicitar Reembolso',
                                    message: '¿Estás seguro de que quieres solicitar un reembolso para esta orden?',
                                    confirmText: 'Sí, solicitar',
                                    cancelText: 'No',
                                    color: '#AA5FC7',
                                    accent: '#89CF6D',
                                    showCancel: true,
                                    onConfirm: () => this.submit(),
                                    onCancel: () => {} });">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-orange-300 rounded-md text-sm font-medium text-orange-700 bg-white hover:bg-orange-50 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        Solicitar Reembolso
                                    </button>
                                </form>
                            @endif
                            
                            @if($order->status === 'shipped')
                                <form action="{{ route('orders.mark-received', $order->id) }}" method="POST" onsubmit="event.preventDefault(); showModalAlert({
                                    title: 'Marcar como Recibida',
                                    message: '¿Confirmas que has recibido esta orden?',
                                    confirmText: 'Sí, recibida',
                                    cancelText: 'No',
                                    color: '#49874E',
                                    accent: '#AA5FC7',
                                    showCancel: true,
                                    onConfirm: () => this.submit(),
                                    onCancel: () => {} });">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Marcar como Recibida
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('orders.reorder', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reordenar Productos
                                </button>
                            </form>
                            
                            <a href="{{ route('orders.invoice', $order->id) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Descargar Factura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/modal-alert.js') }}"></script>
@endsection

