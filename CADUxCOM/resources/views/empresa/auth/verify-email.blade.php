<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo - Empresa</title>
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; background:#f7fafc; color:#1a202c; }
        .container { max-width: 640px; margin: 40px auto; background:#fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); padding: 24px; }
        h1 { font-size: 22px; margin-bottom: 12px; }
        p { margin: 8px 0; }
        .note { background:#f0f5ff; border:1px solid #c3dafe; padding:12px; border-radius:6px; margin-top:12px; }
        .btn { display:inline-block; background:#2f855a; color:#fff; padding:10px 16px; border-radius:6px; text-decoration:none; margin-top:12px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Confirma tu correo electrónico</h1>
    <p>Te enviamos un enlace de verificación a tu correo. Revisa tu bandeja de entrada y haz clic en el botón para completar la verificación.</p>
    <div class="note">
        <p>Si no te ha llegado, vuelve al formulario de registro y verifica que tu correo esté correcto. También revisa tu carpeta de spam.</p>
    </div>
    <a class="btn" href="{{ route('empresa.login') }}">Ir al inicio de sesión</a>
    <p style="margin-top:16px;font-size:12px;color:#4a5568;">Una vez verificado tu correo y aprobada tu cuenta, podrás acceder al panel de empresa.</p>
    @if(session('success'))
        <p style="color:#2f855a;">{{ session('success') }}</p>
    @endif
    @if($errors->any())
        <p style="color:#c53030;">{{ $errors->first() }}</p>
    @endif
    </div>
</body>
</html>