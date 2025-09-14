<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable OrderStatusUpdateMail - Email de actualización de estado de orden
 * 
 * Este mailable se envía cuando el estado de una orden cambia
 * para mantener informado al cliente sobre el progreso de su compra.
 */
class OrderStatusUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * La orden que se está actualizando
     */
    public Order $order;

    /**
     * El estado anterior de la orden
     */
    public string $previousStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $previousStatus)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusMessages = [
            Order::STATUS_PAID => 'Pago Confirmado',
            Order::STATUS_PROCESSING => 'En Procesamiento',
            Order::STATUS_SHIPPED => 'Enviada',
            Order::STATUS_DELIVERED => 'Entregada',
            Order::STATUS_CANCELLED => 'Cancelada',
            Order::STATUS_REFUNDED => 'Reembolsada',
        ];

        $statusMessage = $statusMessages[$this->order->status] ?? 'Actualización';

        return new Envelope(
            subject: "{$statusMessage} - Orden #{$this->order->order_number} - CADUxCOM",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-update',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->customer_name,
                'orderNumber' => $this->order->order_number,
                'currentStatus' => $this->order->status,
                'currentStatusInSpanish' => $this->order->getStatusInSpanish(),
                'previousStatus' => $this->previousStatus,
                'trackingNumber' => $this->order->tracking_number,
                'trackingUrl' => route('orders.show', $this->order->id),
                'estimatedDelivery' => $this->order->estimated_delivery,
                'shippedAt' => $this->order->shipped_at?->format('d/m/Y H:i'),
                'deliveredAt' => $this->order->delivered_at?->format('d/m/Y H:i'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}