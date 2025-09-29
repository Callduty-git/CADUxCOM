<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Mail\EmpresaApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmpresaVerificationController extends Controller
{
    /**
     * Mostrar lista de empresas pendientes de verificación
     */
    public function index()
    {
        $empresasPendientes = Empresa::where('status', 'pending')
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

        return Storage::disk('public')->download(
            $empresa->Certificado_Camara_de_comercio,
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

        return Storage::disk('public')->response($empresa->Foto);
    }
}