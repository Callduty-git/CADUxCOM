<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Empresa;

class EmpresaProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $empresa = Auth::guard('empresa')->user();
            if (!$empresa) {
                return response()->json(['message' => 'No autorizado. Debes iniciar sesión.'], 401);
            }

            $request->validate([
                'Nombre' => 'required|string|max:255',
                'email' => 'required|email|unique:empresas,email,' . $empresa->Id_Empresa . ',Id_Empresa',
                'Direccion' => 'nullable|string|max:255',
                'Municipio' => 'nullable|string|max:255',
                'Ubicacion' => 'nullable|string|max:255',
                'Contacto' => 'nullable|string|max:20',
                'NIT' => 'nullable|string|max:50',
                'Foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                
                // CAMBIO AQUI: Usar la validación mimetypes para PDFs
                'Certificado_Camara_de_comercio' => 'nullable|file|max:4096',
            ]);

            $empresa->Nombre = $request->Nombre;
            $empresa->email = $request->email;
            $empresa->Direccion = $request->Direccion;
            $empresa->Municipio = $request->Municipio;
            $empresa->Ubicacion = $request->Ubicacion;
            $empresa->Contacto = $request->Contacto;
            $empresa->NIT = $request->NIT;

            // Procesar archivos si existen
            if ($request->hasFile('Foto')) {
                if ($empresa->Foto) {
                    Storage::disk('public')->delete($empresa->Foto);
                }
                $pathFoto = $request->file('Foto')->store('empresas/fotos', 'public');
                $empresa->Foto = $pathFoto;
            }

            if ($request->hasFile('Certificado_Camara_de_comercio')) {
                if ($empresa->Certificado_Camara_de_comercio) {
                    Storage::disk('public')->delete($empresa->Certificado_Camara_de_comercio);
                }
                $pathCertificado = $request->file('Certificado_Camara_de_comercio')->store('empresas/certificados', 'public');
                $empresa->Certificado_Camara_de_comercio = $pathCertificado;
            }

            $empresa->save();

            return response()->json(['success' => true, 'message' => 'Perfil actualizado correctamente ✅']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hubo un problema al actualizar el perfil ❌. ' . $e->getMessage()], 500);
        }
    }
}