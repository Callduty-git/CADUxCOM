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
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validación común
        $request->validate([
            'name' => ['required_if:role,empresa,usuario', 'string', 'max:255'],
            'email' => ['required_if:role,usuario', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:usuario,empresa'],

            // Validaciones específicas para empresas
            'foto' => ['required_if:role,empresa', 'image'],
            'direccion' => ['required_if:role,empresa', 'string', 'max:255'],
            'municipio' => ['required_if:role,empresa', 'string', 'max:255'],
            'ubicacion' => ['nullable', 'string', 'max:255'],
            'contacto' => ['required_if:role,empresa', 'string', 'max:50'],
            'email_empresa' => ['required_if:role,empresa', 'email', 'max:255', 'unique:empresas,email'],
            'nit' => ['required_if:role,empresa', 'string', 'max:50', 'unique:empresas,NIT'],
            'certificado_camara_de_comercio' => ['required_if:role,empresa', 'file', 'mimes:pdf,jpg,png'],
        ]);

        // Si es un usuario normal
        if ($request->role === 'usuario') {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            event(new Registered($user));
            Auth::login($user);

            return redirect(route('dashboard'));
        }

        // Si es una empresa
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
                'email' => $request->email_empresa,
                'NIT' => $request->nit,
                'Certificado_Camara_de_comercio' => $certificadoPath,
                'password' => Hash::make($request->password),
            ]);

            // Registrar evento y loguear con guard 'empresa'
            event(new Registered($empresa));
            Auth::guard('empresa')->login($empresa);

            // Para depuración (ver en storage/logs/laravel.log)
            Log::info('Empresa registrada exitosamente', $empresa->toArray());

            return redirect()->route('productos.create'); // Asegúrate que esta ruta exista
        }

        return back()->withErrors(['role' => 'Rol no válido']);
    }
}
