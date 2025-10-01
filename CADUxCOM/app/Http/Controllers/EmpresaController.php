<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\LogEmpresa;

class EmpresaController extends Controller
{
    /**
     * Muestra el dashboard de la empresa con sus datos.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Obtener el usuario autenticado (la empresa) usando el guard 'empresa'
        // Esto asignará la instancia del modelo Empresa a la variable $empresa
        $empresa = Auth::guard('empresa')->user();

        // Pasar la variable $empresa a la vista 'empresa.dashboard'
        // El método compact('empresa') es una forma abreviada de ['empresa' => $empresa]
        return view('empresa.dashboard', compact('empresa'));
    }

    /**
     * Eliminar la cuenta de la empresa autenticada.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function eliminarCuenta(Request $request)
    {
        try {
            // Obtener la empresa autenticada
            /** @var \App\Models\Empresa $empresa */
            $empresa = Auth::guard('empresa')->user();
            
            if (!$empresa) {
                return redirect()->route('empresa.login')
                    ->with('error', 'Debes iniciar sesión para realizar esta acción.');
            }

            // Log del inicio del proceso de eliminación
            Log::info('Iniciando eliminación de cuenta de empresa', [
                'empresa_id' => $empresa->Id_Empresa,
                'empresa_nombre' => $empresa->Nombre
            ]);

            // Usar transacción para garantizar integridad de datos
            DB::transaction(function () use ($empresa) {
                // Obtener todos los productos de la empresa para eliminar sus archivos
                $productos = Producto::where('Id_Empresa', $empresa->Id_Empresa)->get();
                
                // Eliminar archivos de productos (fotos)
                foreach ($productos as $producto) {
                    if ($producto->Foto && Storage::disk('public')->exists($producto->Foto)) {
                        Storage::disk('public')->delete($producto->Foto);
                    }
                }

                // Eliminar todos los productos de la empresa
                Producto::where('Id_Empresa', $empresa->Id_Empresa)->delete();

                // Eliminar todos los logs de la empresa
                LogEmpresa::where('empresa_id', $empresa->Id_Empresa)->delete();

                // Eliminar archivos de la empresa (foto y certificado)
                if ($empresa->Foto && Storage::disk('public')->exists($empresa->Foto)) {
                    Storage::disk('public')->delete($empresa->Foto);
                }
                if ($empresa->Certificado_Camara_de_comercio && Storage::disk('public')->exists($empresa->Certificado_Camara_de_comercio)) {
                    Storage::disk('public')->delete($empresa->Certificado_Camara_de_comercio);
                }

                // Eliminar la empresa
                $empresa->delete();
            });

            // Cerrar sesión después de la eliminación exitosa
            Auth::guard('empresa')->logout();

            // Invalidar la sesión
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Log del éxito
            Log::info('Cuenta de empresa eliminada exitosamente', [
                'empresa_id' => $empresa->Id_Empresa
            ]);

            // Redirigir a la página principal con mensaje de confirmación
            return redirect()->route('home')
                ->with('success', 'Tu cuenta ha sido eliminada exitosamente.');

        } catch (\Exception $e) {
            // Log del error
            Log::error('Error al eliminar cuenta de empresa', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // En caso de error, redirigir con mensaje de error
            return back()->with('error', 'Ocurrió un error al eliminar la cuenta. Por favor, inténtalo de nuevo.');
        }
    }
}
