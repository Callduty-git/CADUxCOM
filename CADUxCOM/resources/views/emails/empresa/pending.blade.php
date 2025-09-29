@component('mail::message')
# Hola {{ $empresa->Nombre }}

Tu empresa ha sido registrada exitosamente en **Caduxcom**.  
Actualmente tu cuenta está en revisión. Te notificaremos una vez haya sido verificada.  

📌 Estos son los datos que registraste:

- **Nombre:** {{ $empresa->Nombre }}
- **Correo electrónico:** {{ $empresa->email }}
- **Teléfono de contacto:** {{ $empresa->Contacto }}
- **Dirección:** {{ $empresa->Direccion }}
- **Municipio:** {{ $empresa->Municipio }}
- **Código Postal:** {{ $empresa->postal_code ?? 'No especificado' }}
- **NIT:** {{ $empresa->NIT }}
- **Certificado Cámara de Comercio:**  
  @if($empresa->Certificado_Camara_de_comercio)
  [Ver Certificado]({{ asset('storage/'.$empresa->Certificado_Camara_de_comercio) }})
  @else
  No adjuntado
  @endif

Gracias por confiar en **Caduxcom**.  
Pronto recibirás noticias nuestras.

Saludos,<br>
El equipo de {{ config('app.name') }}
@endcomponent
