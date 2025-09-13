<?php

namespace App\Mail;

use App\Models\Producto;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DiscountAlertNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $producto;
    public $user;
    public $discountPercentage;

    /**
     * Create a new message instance.
     */
    public function __construct(Producto $producto, User $user, float $discountPercentage)
    {
        $this->producto = $producto;
        $this->user = $user;
        $this->discountPercentage = $discountPercentage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💰 ¡Gran descuento disponible! - ' . $this->producto->Nombre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.discount-alert',
            with: [
                'producto' => $this->producto,
                'user' => $this->user,
                'discountPercentage' => $this->discountPercentage,
            ]
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