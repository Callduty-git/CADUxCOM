@extends('layouts.app')

@section('title', 'Mis Órdenes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mis Órdenes</h1>
            <p class="text-gray-600">Historial de todas tus compras</p>
        </div>

        @if($orders->count() > 0)
            <!-- Lista de órdenes -->
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Header de la orden -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            Orden #{{ $order->order_number }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $order->getStatusInSpanish() }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Realizada el {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                
                                <div class="mt-4 sm:mt-0 sm:ml-4">
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">
                                            ${{ number_format($order->total_amount, 0, ',', '.') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $order->items->count() }} producto(s)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Productos de la orden -->
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($order->items->take(3) as $item)
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $item->product_image ? asset('storage/' . $item->product_image) : asset('images/default-product.png') }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="w-12 h-12 object-cover rounded">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $item->product_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Cantidad: {{ $item->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($order->items->count() > 3)
                                    <div class="flex items-center justify-center">
                                        <span class="text-sm text-gray-500">
                                            +{{ $order->items->count() - 3 }} producto(s) más
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Información adicional -->
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Método de pago:</span>
                                    <span class="font-medium">{{ $order->getPaymentMethodInSpanish() }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Envío a:</span>
                                    <span class="font-medium">{{ $order->shipping_city }}, {{ $order->shipping_state }}</span>
                                </div>
                                @if($order->tracking_number)
                                    <div>
                                        <span class="text-gray-600">Tracking:</span>
                                        <span class="font-medium text-blue-600">{{ $order->tracking_number }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Acciones -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('orders.show', $order->id) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Detalles
                                </a>
                                
                                @if($order->canBeCancelled())
                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('¿Estás seguro de que quieres cancelar esta orden?')"
                                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Cancelar Orden
                                        </button>
                                    </form>
                                @endif
                                
                                @if($order->canBeRefunded())
                                    <form action="{{ route('orders.refund', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('¿Estás seguro de que quieres solicitar un reembolso para esta orden?')"
                                                class="inline-flex items-center px-4 py-2 border border-orange-300 rounded-md text-sm font-medium text-orange-700 bg-white hover:bg-orange-50 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            Solicitar Reembolso
                                        </button>
                                    </form>
                                @endif
                                
                                @if($order->status === 'shipped')
                                    <form action="{{ route('orders.mark-received', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('¿Confirmas que has recibido esta orden?')"
                                                class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Marcar como Recibida
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reordenar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <!-- Sin órdenes -->
            <div class="text-center py-16">
                <div class="mb-6">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No tienes órdenes aún</h3>
                <p class="text-gray-600 mb-6">Cuando realices tu primera compra, aparecerá aquí</p>
                <a href="{{ route('productos.public.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Comenzar a Comprar
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

