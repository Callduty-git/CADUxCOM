<?php

namespace App\Mail;

use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmpresaRegistrationNotification extends Mailable
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
        return $this->subject('Nueva empresa registrada en Caduxcom')
                    ->markdown('emails.empresa.registration')
                    ->with([
                        'empresa' => $this->empresa,
                    ]);
    }
}
