<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Mail\EmpresaApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Producto;
use App\Models\LogEmpresa;
use App\Models\Notification;

class EmpresaVerificationController extends Controller
{
    /**
     * Mostrar lista de empresas pendientes de verificación
     */
    public function index()
    {
        // Incluir cuentas en modo Sandbox como pendientes de verificación
        $empresasPendientes = Empresa::whereIn('status', ['pending', 'sandbox'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.empresas.pending', compact('empresasPendientes'));
    }

    /**
     * Mostrar detalles de una empresa específica
     */
    public function show(Empresa $empresa)
    {
        return view('admin.empresas.show', compact('empresa'));
    }

    /**
     * Aprobar una empresa
     */
    public function approve(Request $request, Empresa $empresa)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500'
        ]);

        $empresa->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Enviar email de aprobación a la empresa
        Mail::to($empresa->email)->send(new EmpresaApprovalNotification($empresa, true));

        // Crear notificación in-app para la empresa aprobada
        Notification::create([
            'type' => 'account_status',
            'title' => 'Cuenta aprobada',
            'message' => '¡Tu empresa ha sido aprobada! Ya estás en modo producción.',
            'data' => ['status' => 'approved'],
            'empresa_id' => $empresa->Id_Empresa,
            'priority' => 'medium',
            'channel' => 'in_app',
        ]);

        return redirect()->route('admin.empresas.pending')
            ->with('success', "La empresa {$empresa->Nombre} ha sido aprobada exitosamente.");
    }

    /**
     * Rechazar una empresa
     */
    public function reject(Request $request, Empresa $empresa)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $empresa->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Enviar email de rechazo a la empresa
        Mail::to($empresa->email)->send(new EmpresaApprovalNotification($empresa, false));

        return redirect()->route('admin.empresas.pending')
            ->with('success', "La empresa {$empresa->Nombre} ha sido rechazada.");
    }

    /**
     * Mostrar empresas aprobadas
     */
    public function approved()
    {
        $empresasAprobadas = Empresa::where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->paginate(10);

        return view('admin.empresas.approved', compact('empresasAprobadas'));
    }

    /**
     * Mostrar empresas rechazadas
     */
    public function rejected()
    {
        $empresasRechazadas = Empresa::where('status', 'rejected')
            ->orderBy('rejected_at', 'desc')
            ->paginate(10);

        return view('admin.empresas.rejected', compact('empresasRechazadas'));
    }

    /**
     * Descargar certificado de cámara de comercio
     */
    public function downloadCertificado(Empresa $empresa)
    {
        if (!Storage::disk('public')->exists($empresa->Certificado_Camara_de_comercio)) {
            abort(404, 'Archivo no encontrado');
        }

        $fullPath = Storage::disk('public')->path($empresa->Certificado_Camara_de_comercio);
        return response()->download(
            $fullPath,
            "certificado_camara_comercio_{$empresa->NIT}.pdf"
        );
    }

    /**
     * Ver imagen de la empresa
     */
    public function viewFoto(Empresa $empresa)
    {
        if (!Storage::disk('public')->exists($empresa->Foto)) {
            abort(404, 'Imagen no encontrada');
        }

        $fullPath = Storage::disk('public')->path($empresa->Foto);
        return response()->file($fullPath);
    }

    /**
     * Eliminar una empresa (solo administradores)
     */
    public function destroy(Request $request, Empresa $empresa)
    {
        DB::transaction(function () use ($empresa) {
            Producto::where('Id_Empresa', $empresa->Id_Empresa)->delete();
            LogEmpresa::where('empresa_id', $empresa->Id_Empresa)->delete();

            if ($empresa->Foto && Storage::disk('public')->exists($empresa->Foto)) {
                Storage::disk('public')->delete($empresa->Foto);
            }
            if ($empresa->Certificado_Camara_de_comercio && Storage::disk('public')->exists($empresa->Certificado_Camara_de_comercio)) {
                Storage::disk('public')->delete($empresa->Certificado_Camara_de_comercio);
            }

            $empresa->delete();
        });

        $returnTo = $request->query('return_to');
        $redirectRoute = match ($returnTo) {
            'approved' => 'admin.empresas.approved',
            'rejected' => 'admin.empresas.rejected',
            default => 'admin.empresas.pending',
        };

        return redirect()->route($redirectRoute)
            ->with('success', 'La empresa fue eliminada exitosamente.');
    }
}