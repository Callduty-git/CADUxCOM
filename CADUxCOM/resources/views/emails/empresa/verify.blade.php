@component('mail::message')
# Verifica tu correo

Hola {{ $empresa->Nombre }},

Gracias por registrarte en **CADUxCOM**. Antes de poder acceder a tu cuenta, necesitamos que confirmes tu correo electrónico.

@component('mail::button', ['url' => $verificationUrl])
Verificar correo
@endcomponent

Este enlace caduca en 60 minutos. Si no solicitaste esta verificación, puedes ignorar este mensaje.

Gracias,
{{ config('app.name') }}
@endcomponent