<?php

namespace App\Mail;

use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class EmpresaEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public Empresa $empresa;

    /**
     * Create a new message instance.
     */
    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'empresa.verification.verify',
            now()->addMinutes(60),
            [
                'id' => $this->empresa->Id_Empresa,
                'hash' => sha1($this->empresa->email),
            ]
        );

        return $this->subject('Verifica tu correo - CADUxCOM')
            ->markdown('emails.empresa.verify')
            ->with([
                'empresa' => $this->empresa,
                'verificationUrl' => $verificationUrl,
            ]);
    }
}