<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use App\Mail\EmpresaRegistrationNotification;
use App\Mail\EmpresaPendingVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

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
                'email_empresa' => ['required', 'string', 'email', 'max:255', 'unique:empresas,email'],
                'direccion' => ['required', 'string', 'max:255'],
                'municipio' => ['required', 'string', 'max:255'],
                'ubicacion' => ['nullable', 'string', 'max:255'],
                'contacto' => ['required', 'string', 'max:50'],
                'nit' => ['required', 'string', 'max:50', 'unique:empresas,NIT'],
                'foto' => ['required', 'image'],
                'certificado_camara_de_comercio' => ['required', 'file', 'mimes:pdf,jpg,png'],
            ];
        }

        // Validar todo
        Validator::make(
            $request->all(),
            array_merge($baseRules, $additionalRules)
        )->validate();

        /**
         * Registro usuario normal (entra directo sin verificación ni correos al admin)
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

            return redirect()->route('dashboard')
                ->with('success', 'Registro exitoso. Bienvenido a tu panel.');
        }

        /**
         * Registro empresa (requiere verificación)
         */
        if ($request->role === 'empresa') {
            $fotoPath = $request->file('foto')->store('empresas/fotos', 'public');
            $certificadoPath = $request->file('certificado_camara_de_comercio')->store('empresas/certificados', 'public');

            $empresa = Empresa::create([
                'Nombre' => $request->name,
                'Foto' => $fotoPath,
                'Direccion' => $request->direccion,
                'Municipio' => $request->municipio,
                'Ubicacion' => $request->ubicacion,
                'Contacto' => $request->contacto,
                'email' => $request->email_empresa,
                'NIT' => $request->nit,
                'Certificado_Camara_de_comercio' => $certificadoPath,
                'password' => Hash::make($request->password),
                'status' => 'pending',
            ]);

            // Notificar al admin
            $adminEmail = 'caduxcom.store@gmail.com';
            Mail::to($adminEmail)->send(new EmpresaRegistrationNotification($empresa));

            // Notificar a la empresa
            Mail::to($empresa->email)->send(new EmpresaPendingVerification($empresa));

            Log::info('Empresa registrada exitosamente (pendiente de verificación)', $empresa->toArray());

            return redirect()->route('login')
                ->with('success', 'Registro de empresa exitoso. Tu solicitud está en verificación. Recibirás un correo cuando sea aprobada.');
        }

        return back()->withErrors(['role' => 'Rol no válido']);
    }
}
