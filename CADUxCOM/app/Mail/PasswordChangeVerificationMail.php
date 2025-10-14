<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangeVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->verificationUrl = route('password.verify', [
            'email' => $user->email,
            'token' => $token
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔒 Verificación para Cambiar Contraseña - CADUxCOM',
            from: 'caduxcom.store@gmail.com',
            replyTo: 'caduxcom.store@gmail.com',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-change-verification',
            with: [
                'user' => $this->user,
                'token' => $this->token,
                'verificationUrl' => $this->verificationUrl,
                'expirationTime' => '1 hora'
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