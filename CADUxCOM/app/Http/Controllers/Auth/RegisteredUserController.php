<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa; // Asegúrate de tener este modelo creado
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:usuario,empresa'],  // Validar rol
            
            // Validaciones para empresa (solo si rol es empresa)
            'foto' => ['required_if:role,empresa', 'image'],
            'direccion' => ['required_if:role,empresa', 'string', 'max:255'],
            'municipio' => ['required_if:role,empresa', 'string', 'max:255'],
            'ubicacion' => ['required_if:role,empresa', 'string', 'max:255'],
            'contacto' => ['required_if:role,empresa', 'string', 'max:50'],
            'email_empresa' => ['required_if:role,empresa', 'email', 'max:255'],
            'nit' => ['required_if:role,empresa', 'string', 'max:50'],
            'certificado_camara_de_comercio' => ['required_if:role,empresa', 'file', 'mimes:pdf,jpg,png'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        $user = User::create($userData);

        if ($request->role === 'empresa') {
            // Guardar archivos en disco (usar disco 'public', asegúrate que esté configurado)
            $fotoPath = $request->file('foto')->store('empresas/fotos', 'public');
            $certificadoPath = $request->file('certificado_camara_de_comercio')->store('empresas/certificados', 'public');

            Empresa::create([
                'user_id' => $user->id,
                'foto' => $fotoPath,
                'direccion' => $request->direccion,
                'municipio' => $request->municipio,
                'ubicacion' => $request->ubicacion,
                'contacto' => $request->contacto,
                'email' => $request->email_empresa,
                'nit' => $request->nit,
                'certificado_camara_de_comercio' => $certificadoPath,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->role === 'empresa') {
            return redirect()->route('productos.create');
        }

        return redirect(route('dashboard'));
    }
}
