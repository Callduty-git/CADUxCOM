<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Mail\EmpresaRegistrationNotification;
use App\Mail\UserRegistrationNotification;
use App\Mail\EmpresaPendingVerification;
use App\Mail\EmpresaEmailVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use App\Models\Notification;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar formulario de registro.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro de usuario o empresa.
     */
    public function store(Request $request): RedirectResponse
    {
        // Reglas comunes
        $baseRules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:usuario,empresa'],
        ];

        // Reglas específicas por rol
        $additionalRules = [];

        if ($request->role === 'usuario') {
            $additionalRules = [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            ];
        } elseif ($request->role === 'empresa') {
            $additionalRules = [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:empresas,email'],
                'email_empresa' => ['required', 'string', 'email', 'max:255', 'unique:empresas,email_empresa'],
                'direccion' => ['required', 'string', 'max:255'],
                'municipio' => ['required', 'string', 'max:255'],
                'ubicacion' => ['nullable', 'string', 'max:255'],
                'contacto' => ['required', 'numeric', 'digits_between:1,10'],
                'nit' => ['required', 'numeric', 'digits_between:8,15', 'unique:empresas,NIT'],
                'foto' => ['required', 'image', 'max:2048'],
                'certificado_camara_de_comercio' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
                'terms' => ['accepted'],
            ];
        }

        // Mensajes de error personalizados en español
        $messages = [
            'contacto.required' => 'El número de contacto es obligatorio.',
            'contacto.numeric' => 'El número de contacto debe contener solo números.',
            'contacto.digits_between' => 'El número de contacto debe tener máximo 10 dígitos.',
            'nit.required' => 'El NIT es obligatorio.',
            'nit.numeric' => 'El NIT debe contener solo números.',
            'nit.digits_between' => 'El NIT debe tener entre 8 y 15 dígitos.',
            'nit.unique' => 'Este NIT ya está registrado en el sistema.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'email_empresa.required' => 'El email de la empresa es obligatorio.',
            'email_empresa.email' => 'El email de la empresa debe tener un formato válido.',
            'email_empresa.unique' => 'Este email de empresa ya está registrado.',
            'name.required' => 'El nombre es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'direccion.required' => 'La dirección es obligatoria.',
            'municipio.required' => 'El municipio es obligatorio.',
            'ubicacion.max' => 'La ubicación no debe superar los 255 caracteres.',
            'foto.required' => 'La foto de la empresa es obligatoria.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.max' => 'La imagen no debe superar los 2MB.',
            'certificado_camara_de_comercio.required' => 'El certificado de cámara de comercio es obligatorio.',
            'certificado_camara_de_comercio.mimes' => 'El certificado debe ser un archivo PDF, JPG, JPEG o PNG.',
            'certificado_camara_de_comercio.max' => 'El certificado no debe superar los 5MB.',
            'terms.accepted' => 'Debes aceptar los términos y condiciones.',
            'role.required' => 'Debes seleccionar un tipo de cuenta.',
            'role.in' => 'El tipo de cuenta seleccionado no es válido.',
        ];

        // Validación
        $validator = Validator::make(
            $request->all(),
            array_merge($baseRules, $additionalRules),
            $messages
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        /**
         * Registro de usuario común
         */
        if ($request->role === 'usuario') {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'usuario',
            ]);

            event(new Registered($user));
            Auth::login($user);

            // Notificar al administrador
            try {
                $adminEmail = 'caduxcom.store@gmail.com';
                Mail::to($adminEmail)->send(new UserRegistrationNotification($user));
            } catch (\Exception $e) {
                Log::error('Error enviando notificación de registro de usuario: '.$e->getMessage(), [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }

            return redirect()->route('verification.notice')
                ->with('success', 'Registro exitoso. Te enviamos un enlace para verificar tu correo.')
                ->with('status', 'verification-link-sent');
        }

        /**
         * Registro de empresa (modo sandbox + verificación)
         */
        if ($request->role === 'empresa') {
            // Guardar archivos
            $fotoPath = $request->file('foto')->store('empresas/fotos', 'public');
            $certificadoPath = $request->file('certificado_camara_de_comercio')->store('empresas/certificados', 'public');

            // Crear empresa
            $empresa = Empresa::create([
                'Nombre' => $request->name,
                'Foto' => $fotoPath,
                'Direccion' => $request->direccion,
                'Municipio' => $request->municipio,
                'Ubicacion' => $request->ubicacion,
                'Contacto' => $request->contacto,
                'email' => $request->email, // Email de acceso para login
                'email_empresa' => $request->email_empresa, // Email de contacto comercial
                'NIT' => $request->nit,
                'Certificado_Camara_de_comercio' => $certificadoPath,
                'password' => Hash::make($request->password),
                'status' => 'sandbox', // Empresa entra en modo sandbox
            ]);

            try {
                $adminEmail = 'caduxcom.store@gmail.com';

                // Notificar al administrador
                Mail::to($adminEmail)->send(new EmpresaRegistrationNotification($empresa));

                // Notificar a la empresa
                Mail::to($empresa->email)->send(new EmpresaPendingVerification($empresa));
                Mail::to($empresa->email)->send(new EmpresaEmailVerification($empresa));

                Log::info('Empresa registrada exitosamente (modo sandbox activado)', $empresa->toArray());
            } catch (\Exception $e) {
                Log::error('Error enviando correos de registro de empresa: '.$e->getMessage(), [
                    'empresa_id' => $empresa->id,
                    'email' => $empresa->email,
                ]);
            }

            return redirect()->route('login')
                ->with('success', 'Registro de empresa exitoso. Te enviamos un enlace para verificar tu correo. 
                Luego podrás ingresar en modo Sandbox mientras validamos tu información.');
        }

        return back()->withErrors(['role' => 'Rol no válido']);
    }
}
