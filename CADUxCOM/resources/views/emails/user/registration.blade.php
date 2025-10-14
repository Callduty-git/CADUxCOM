@component('mail::message')
# Nuevo usuario registrado en Caduxcom

Se ha registrado un nuevo usuario.

- **Nombre:** {{ $user->name }}
- **Correo electrónico:** {{ $user->email }}
- **Fecha de registro:** {{ $user->created_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}

@component('mail::button', ['url' => url('/admin/login')])
Ver usuarios (panel admin)
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent