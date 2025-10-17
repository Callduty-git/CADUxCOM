<?php

namespace App\Services;

use App\Models\EmpresaNotification;
use App\Models\Order;
use App\Models\Empresa;

class NotificationService
{
    /**
     * Crear notificación de nuevo pedido para la empresa
     */
    public function createNewOrderNotification(Order $order): void
    {
        // Obtener todas las empresas involucradas en el pedido
        $empresaIds = $order->items()->with('product.empresa')
            ->get()
            ->pluck('product.empresa.Id_Empresa')
            ->unique();

        foreach ($empresaIds as $empresaId) {
            $empresa = Empresa::find($empresaId);
            if (!$empresa) continue;

            // Calcular el total de productos de esta empresa en el pedido
            $empresaTotal = $order->items()
                ->whereHas('product', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->sum('total_price');

            // Contar productos de esta empresa en el pedido
            $productCount = $order->items()
                ->whereHas('product', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->count();

            EmpresaNotification::createForEmpresa(
                $empresaId,
                'new_order',
                'Nuevo Pedido Recibido',
                "Has recibido un nuevo pedido #{$order->id} con {$productCount} producto(s) por un total de $" . number_format($empresaTotal, 2),
                [
                    'order_id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $empresaTotal,
                    'product_count' => $productCount,
                    'order_status' => $order->status,
                ]
            );
        }
    }

    /**
     * Crear notificación de cambio de estado de pedido
     */
    public function createOrderStatusNotification(Order $order, string $oldStatus, string $newStatus): void
    {
        $empresaIds = $order->items()->with('product.empresa')
            ->get()
            ->pluck('product.empresa.Id_Empresa')
            ->unique();

        foreach ($empresaIds as $empresaId) {
            $statusMessages = [
                'pending' => 'está pendiente',
                'confirmed' => 'ha sido confirmado',
                'processing' => 'está siendo procesado',
                'shipped' => 'ha sido enviado',
                'delivered' => 'ha sido entregado',
                'cancelled' => 'ha sido cancelado',
            ];

            $message = "El pedido #{$order->id} " . ($statusMessages[$newStatus] ?? "cambió de estado a {$newStatus}");

            EmpresaNotification::createForEmpresa(
                $empresaId,
                'order_status_change',
                'Cambio de Estado de Pedido',
                $message,
                [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'customer_name' => $order->customer_name,
                ]
            );
        }
    }

    /**
     * Crear notificación de producto con bajo stock
     */
    public function createLowStockNotification(int $empresaId, $product): void
    {
        EmpresaNotification::createForEmpresa(
            $empresaId,
            'low_stock',
            'Stock Bajo',
            "El producto '{$product->Nombre}' tiene stock bajo ({$product->Stock} unidades restantes)",
            [
                'product_id' => $product->Id_Producto,
                'product_name' => $product->Nombre,
                'current_stock' => $product->Stock,
            ]
        );
    }

    /**
     * Obtener notificaciones no leídas para una empresa
     */
    public function getUnreadNotifications(int $empresaId, int $limit = 10)
    {
        return EmpresaNotification::where('empresa_id', $empresaId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Marcar todas las notificaciones como leídas para una empresa
     */
    public function markAllAsRead(int $empresaId): void
    {
        EmpresaNotification::where('empresa_id', $empresaId)
            ->unread()
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Obtener el conteo de notificaciones no leídas
     */
    public function getUnreadCount(int $empresaId): int
    {
        return EmpresaNotification::where('empresa_id', $empresaId)
            ->unread()
            ->count();
    }
}