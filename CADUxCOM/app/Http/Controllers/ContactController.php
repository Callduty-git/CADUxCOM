<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Mostrar la página de contacto
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Enviar el mensaje de contacto
     */
    public function send(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'subject.required' => 'El asunto es obligatorio.',
            'subject.max' => 'El asunto no puede tener más de 255 caracteres.',
            'message.required' => 'El mensaje es obligatorio.',
            'message.max' => 'El mensaje no puede tener más de 2000 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Aquí puedes implementar el envío del email
            // Por ahora, solo simularemos el envío exitoso
            
            // Datos del mensaje
            $contactData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'timestamp' => now()->format('d/m/Y H:i:s'),
            ];

            // TODO: Implementar el envío real del email
            // Mail::to('contacto@caduxcom.com')->send(new ContactMail($contactData));

            // Log del mensaje (opcional)
            \Log::info('Nuevo mensaje de contacto recibido', $contactData);

            return redirect()->back()->with('success', 
                '¡Mensaje enviado correctamente! Te responderemos en menos de 24 horas.'
            );

        } catch (\Exception $e) {
            \Log::error('Error al enviar mensaje de contacto: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.'])
                ->withInput();
        }
    }
}



