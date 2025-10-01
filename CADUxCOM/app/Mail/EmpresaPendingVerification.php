<?php

namespace App\Mail;

use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmpresaPendingVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $empresa;

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
        return $this->subject('Registro recibido - Tu empresa está en revisión')
                    ->markdown('emails.empresa.pending')
                    ->with([
                        'empresa' => $this->empresa,
                    ]);
    }
}
