<?php

namespace App\Mail;

use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmpresaApprovalNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $empresa;
    public $approved;

    /**
     * Create a new message instance.
     */
    public function __construct(Empresa $empresa, bool $approved = true)
    {
        $this->empresa = $empresa;
        $this->approved = $approved;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->approved 
            ? '¡Registro Aprobado! - CADUxCOM' 
            : 'Registro No Aprobado - CADUxCOM';
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->approved 
            ? 'emails.empresa-approved' 
            : 'emails.empresa-rejected';
            
        return new Content(
            view: $view,
            with: [
                'empresa' => $this->empresa,
                'approvalDate' => now()->format('d/m/Y H:i:s'),
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

