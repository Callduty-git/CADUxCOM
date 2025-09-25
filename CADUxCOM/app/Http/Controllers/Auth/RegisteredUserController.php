<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

        // Registro usuario
        if ($request->role === 'usuario') {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'usuario',
            ]);

            event(new Registered($user));
            Auth::login($user);
            return redirect()->route('dashboard'); // Redirige a /dashboard tras registro
        }

        // Registro empresa
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
            ]);

            event(new Registered($empresa));

            Auth::guard('empresa')->login($empresa);
            Log::info('Empresa registrada exitosamente', $empresa->toArray());

            return redirect()->route('empresa.dashboard'); // Redirige a dashboard de empresa tras registro
        }

        return back()->withErrors(['role' => 'Rol no válido']);
    }
}
