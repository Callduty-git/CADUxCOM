@component('mail::message')
# Nueva empresa registrada en Caduxcom

Se ha registrado una nueva empresa. Aquí están los datos más importantes:

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

@component('mail::button', ['url' => url('/admin/empresas/'.$empresa->Id_Empresa)])
Revisar Empresa
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
