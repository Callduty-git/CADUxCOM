<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Models\EmpresaNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Obtener notificaciones para la empresa autenticada
     */
    public function index(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();
        
        if (!$empresa) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $notifications = EmpresaNotification::where('empresa_id', $empresa->Id_Empresa)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json($notifications);
        }

        return view('empresa.notifications.index', compact('notifications'));
    }

    /**
     * Obtener notificaciones no leídas (para AJAX)
     */
    public function unread()
    {
        $empresa = Auth::guard('empresa')->user();
        
        if (!$empresa) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $notifications = $this->notificationService->getUnreadNotifications($empresa->Id_Empresa);
        $count = $this->notificationService->getUnreadCount($empresa->Id_Empresa);

        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead($id)
    {
        $empresa = Auth::guard('empresa')->user();
        
        if (!$empresa) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $notification = EmpresaNotification::where('id', $id)
            ->where('empresa_id', $empresa->Id_Empresa)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        $empresa = Auth::guard('empresa')->user();
        
        if (!$empresa) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $this->notificationService->markAllAsRead($empresa->Id_Empresa);

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar una notificación
     */
    public function destroy($id)
    {
        $empresa = Auth::guard('empresa')->user();
        
        if (!$empresa) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $notification = EmpresaNotification::where('id', $id)
            ->where('empresa_id', $empresa->Id_Empresa)
            ->first();

        if (!$notification) {
            return response()->json(['error' => 'Notificación no encontrada'], 404);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
