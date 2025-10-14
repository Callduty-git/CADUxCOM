@component('mail::message')
# Hola {{ $empresa->Nombre }}

Tu empresa ha sido registrada exitosamente en **Caduxcom**.  
Tu cuenta ya está activa en **modo Sandbox** para que puedas ingresar y operar de forma limitada mientras verificamos tus datos.  
Te notificaremos por correo cuando pases a producción.

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
Si tienes dudas, responde a este correo.

Saludos,<br>
El equipo de {{ config('app.name') }}
@endcomponent
