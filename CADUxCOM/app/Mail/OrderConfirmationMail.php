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
 * Mailable OrderConfirmationMail - Email de confirmación de orden
 * 
 * Este mailable se envía cuando se crea una nueva orden
 * para confirmar al cliente que su compra fue procesada.
 */
class OrderConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * La orden que se está confirmando
     */
    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Confirmación de Orden #{$this->order->order_number} - CADUxCOM",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'customerName' => $this->order->customer_name,
                'orderNumber' => $this->order->order_number,
                'orderDate' => $this->order->created_at->format('d/m/Y H:i'),
                'totalAmount' => number_format($this->order->total_amount, 0, ',', '.'),
                'items' => $this->order->items,
                'shippingAddress' => $this->order->shipping_address,
                'shippingCity' => $this->order->shipping_city,
                'shippingState' => $this->order->shipping_state,
                'trackingUrl' => route('orders.show', $this->order->id),
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