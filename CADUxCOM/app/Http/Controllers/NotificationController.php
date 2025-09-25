<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Producto;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Constructor - Aplicar middleware de autenticación
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar todas las notificaciones del usuario
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Notification::query();
        
        // Filtrar por usuario si está autenticado
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id'); // Notificaciones generales
            });
        } else {
            $query->whereNull('user_id'); // Solo notificaciones generales
        }

        // Filtros
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('unread_only') && $request->unread_only) {
            $query->where('is_read', false);
        }

        $notifications = $query->with(['empresa', 'producto'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        // Validar que la notificación pertenezca al usuario autenticado
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para modificar esta notificación.'
            ], 403);
        }

        $notification->markAsRead();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificación marcada como leída'
            ]);
        }

        return back()->with('success', 'Notificación marcada como leída');
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        $query = Notification::query();
        
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id');
            });
        } else {
            $query->whereNull('user_id');
        }

        $query->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Todas las notificaciones marcadas como leídas'
            ]);
        }

        return back()->with('success', 'Todas las notificaciones marcadas como leídas');
    }

    /**
     * Eliminar notificación
     */
    public function destroy(Request $request, Notification $notification)
    {
        // Validar que la notificación pertenezca al usuario autenticado
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar esta notificación.'
            ], 403);
        }

        $notification->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificación eliminada'
            ]);
        }

        return back()->with('success', 'Notificación eliminada');
    }

    /**
     * Obtener notificaciones no leídas (API)
     */
    public function getUnread(Request $request)
    {
        $user = Auth::user();
        
        $query = Notification::query();
        
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id');
            });
        } else {
            $query->whereNull('user_id');
        }

        $notifications = $query->where('is_read', false)
            ->with(['empresa', 'producto'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'priority' => $notification->priority,
                    'priority_class' => $notification->priority_class,
                    'type_icon' => $notification->type_icon,
                    'time_ago' => $notification->time_ago,
                    'data' => $notification->data,
                    'empresa' => $notification->empresa ? [
                        'id' => $notification->empresa->Id_Empresa,
                        'name' => $notification->empresa->Nombre,
                    ] : null,
                    'producto' => $notification->producto ? [
                        'id' => $notification->producto->Id_Producto,
                        'name' => $notification->producto->Nombre,
                        'image' => $notification->producto->Foto ? asset('storage/' . $notification->producto->Foto) : asset('images/default-product.png'),
                    ] : null,
                ];
            });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $notifications->count(),
        ]);
    }

    /**
     * Obtener estadísticas de notificaciones
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        
        $query = Notification::query();
        
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id');
            });
        } else {
            $query->whereNull('user_id');
        }

        $stats = [
            'total' => $query->count(),
            'unread' => $query->where('is_read', false)->count(),
            'by_type' => $query->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'by_priority' => $query->selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
            'recent' => $query->where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
        ]);
    }

    /**
     * Crear notificación manual
     */
    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:expiry_alert,discount_available,new_product,order_update,wishlist_alert,location_alert',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'channel' => 'required|string|in:email,push,sms,in_app',
            'empresa_id' => 'nullable|exists:empresas,Id_Empresa',
            'producto_id' => 'nullable|exists:productos,Id_Producto',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $notification = Notification::create([
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'data' => $request->data ?? [],
            'user_id' => $request->user_id ?? null,
            'empresa_id' => $request->empresa_id,
            'producto_id' => $request->producto_id,
            'priority' => $request->priority,
            'channel' => $request->channel,
            'scheduled_at' => $request->scheduled_at,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notificación creada exitosamente',
                'notification' => $notification,
            ]);
        }

        return back()->with('success', 'Notificación creada exitosamente');
    }

    /**
     * Mostrar detalles de una notificación
     */
    public function show(Notification $notification)
    {
        $notification->load(['empresa', 'producto']);
        
        // Marcar como leída si no lo está
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        return view('notifications.show', compact('notification'));
    }
}