<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AuditController extends Controller
{
    /**
     * Mostrar el dashboard principal de logs y auditoría
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $level = $request->get('level', 'all');
        $module = $request->get('module', 'all');

        // Obtener estadísticas generales
        $stats = $this->getAuditStats($dateFrom, $dateTo);
        
        // Obtener logs recientes
        $recentLogs = $this->getRecentLogs(10);
        
        // Obtener actividad por día
        $dailyActivity = $this->getDailyActivity($dateFrom, $dateTo);
        
        // Obtener distribución por nivel
        $levelDistribution = $this->getLevelDistribution($dateFrom, $dateTo);
        
        // Obtener módulos más activos
        $topModules = $this->getTopModules($dateFrom, $dateTo);

        return view('admin.audit.index', compact(
            'stats', 'recentLogs', 'dailyActivity', 'levelDistribution', 
            'topModules', 'dateFrom', 'dateTo', 'level', 'module'
        ));
    }

    /**
     * Mostrar logs del sistema
     */
    public function systemLogs(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $level = $request->get('level', 'all');
        $search = $request->get('search');

        $logs = $this->getSystemLogs($dateFrom, $dateTo, $level, $search);
        $levels = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

        return view('admin.audit.system-logs', compact('logs', 'levels', 'dateFrom', 'dateTo', 'level', 'search'));
    }

    /**
     * Mostrar logs de actividad de usuarios
     */
    public function userActivity(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $userId = $request->get('user_id');
        $action = $request->get('action', 'all');

        $activities = $this->getUserActivities($dateFrom, $dateTo, $userId, $action);
        $actions = ['login', 'logout', 'register', 'profile_update', 'password_change', 'email_verification'];
        $users = \App\Models\User::select('id', 'name', 'email')->get();

        return view('admin.audit.user-activity', compact('activities', 'actions', 'users', 'dateFrom', 'dateTo', 'userId', 'action'));
    }

    /**
     * Mostrar logs de actividad de empresas
     */
    public function companyActivity(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $companyId = $request->get('company_id');
        $action = $request->get('action', 'all');

        $activities = $this->getCompanyActivities($dateFrom, $dateTo, $companyId, $action);
        $actions = ['register', 'profile_update', 'product_create', 'product_update', 'product_delete', 'status_change'];
        $companies = \App\Models\Empresa::select('id', 'nombre', 'email')->get();

        return view('admin.audit.company-activity', compact('activities', 'actions', 'companies', 'dateFrom', 'dateTo', 'companyId', 'action'));
    }

    /**
     * Mostrar logs de seguridad
     */
    public function securityLogs(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));
        $type = $request->get('type', 'all');
        $ipAddress = $request->get('ip_address');

        $securityLogs = $this->getSecurityLogs($dateFrom, $dateTo, $type, $ipAddress);
        $types = ['failed_login', 'suspicious_activity', 'blocked_ip', 'unauthorized_access', 'password_reset'];

        return view('admin.audit.security-logs', compact('securityLogs', 'types', 'dateFrom', 'dateTo', 'type', 'ipAddress'));
    }

    /**
     * Exportar logs
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'system');
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        switch ($type) {
            case 'system':
                return $this->exportSystemLogs($dateFrom, $dateTo);
            case 'user_activity':
                return $this->exportUserActivity($dateFrom, $dateTo);
            case 'company_activity':
                return $this->exportCompanyActivity($dateFrom, $dateTo);
            case 'security':
                return $this->exportSecurityLogs($dateFrom, $dateTo);
            default:
                return redirect()->back()->with('error', 'Tipo de exportación no válido');
        }
    }

    /**
     * Limpiar logs antiguos
     */
    public function cleanOldLogs(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $days = $request->get('days');
        $cutoffDate = Carbon::now()->subDays($days);

        try {
            // Limpiar logs del sistema
            $deletedSystem = DB::table('system_logs')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            // Limpiar logs de actividad de usuarios
            $deletedUserActivity = DB::table('user_activity_logs')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            // Limpiar logs de actividad de empresas
            $deletedCompanyActivity = DB::table('company_activity_logs')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            // Limpiar logs de seguridad
            $deletedSecurity = DB::table('security_logs')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            $totalDeleted = $deletedSystem + $deletedUserActivity + $deletedCompanyActivity + $deletedSecurity;

            // Registrar la limpieza
            $this->logSystemEvent('audit_cleanup', [
                'days' => $days,
                'cutoff_date' => $cutoffDate,
                'deleted_records' => $totalDeleted,
                'breakdown' => [
                    'system_logs' => $deletedSystem,
                    'user_activity_logs' => $deletedUserActivity,
                    'company_activity_logs' => $deletedCompanyActivity,
                    'security_logs' => $deletedSecurity
                ]
            ]);

            return redirect()->back()->with('success', "Se eliminaron {$totalDeleted} registros de logs anteriores a {$days} días.");

        } catch (\Exception $e) {
            Log::error('Error al limpiar logs antiguos: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al limpiar los logs antiguos.');
        }
    }

    /**
     * Obtener estadísticas de auditoría
     */
    private function getAuditStats($dateFrom, $dateTo)
    {
        $stats = [];

        // Total de logs del sistema
        $stats['total_system_logs'] = DB::table('system_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        // Total de actividad de usuarios
        $stats['total_user_activity'] = DB::table('user_activity_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        // Total de actividad de empresas
        $stats['total_company_activity'] = DB::table('company_activity_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        // Total de logs de seguridad
        $stats['total_security_logs'] = DB::table('security_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        // Errores críticos
        $stats['critical_errors'] = DB::table('system_logs')
            ->whereIn('level', ['emergency', 'alert', 'critical', 'error'])
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        // Intentos de login fallidos
        $stats['failed_logins'] = DB::table('security_logs')
            ->where('type', 'failed_login')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        return $stats;
    }

    /**
     * Obtener logs recientes
     */
    private function getRecentLogs($limit = 10)
    {
        return DB::table('system_logs')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener actividad diaria
     */
    private function getDailyActivity($dateFrom, $dateTo)
    {
        return DB::table('system_logs')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Obtener distribución por nivel
     */
    private function getLevelDistribution($dateFrom, $dateTo)
    {
        return DB::table('system_logs')
            ->selectRaw('level, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('level')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Obtener módulos más activos
     */
    private function getTopModules($dateFrom, $dateTo)
    {
        return DB::table('system_logs')
            ->selectRaw('module, COUNT(*) as count')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->groupBy('module')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Obtener logs del sistema
     */
    private function getSystemLogs($dateFrom, $dateTo, $level, $search)
    {
        $query = DB::table('system_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($level !== 'all') {
            $query->where('level', $level);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('module', 'like', "%{$search}%")
                  ->orWhere('context', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(50);
    }

    /**
     * Obtener actividades de usuarios
     */
    private function getUserActivities($dateFrom, $dateTo, $userId, $action)
    {
        $query = DB::table('user_activity_logs')
            ->leftJoin('users', 'user_activity_logs.user_id', '=', 'users.id')
            ->select('user_activity_logs.*', 'users.name as user_name', 'users.email as user_email')
            ->whereBetween('user_activity_logs.created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($userId) {
            $query->where('user_activity_logs.user_id', $userId);
        }

        if ($action !== 'all') {
            $query->where('user_activity_logs.action', $action);
        }

        return $query->orderBy('user_activity_logs.created_at', 'desc')->paginate(50);
    }

    /**
     * Obtener actividades de empresas
     */
    private function getCompanyActivities($dateFrom, $dateTo, $companyId, $action)
    {
        $query = DB::table('company_activity_logs')
            ->leftJoin('empresas', 'company_activity_logs.company_id', '=', 'empresas.id')
            ->select('company_activity_logs.*', 'empresas.nombre as company_name', 'empresas.email as company_email')
            ->whereBetween('company_activity_logs.created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($companyId) {
            $query->where('company_activity_logs.company_id', $companyId);
        }

        if ($action !== 'all') {
            $query->where('company_activity_logs.action', $action);
        }

        return $query->orderBy('company_activity_logs.created_at', 'desc')->paginate(50);
    }

    /**
     * Obtener logs de seguridad
     */
    private function getSecurityLogs($dateFrom, $dateTo, $type, $ipAddress)
    {
        $query = DB::table('security_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($ipAddress) {
            $query->where('ip_address', 'like', "%{$ipAddress}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate(50);
    }

    /**
     * Registrar evento del sistema
     */
    private function logSystemEvent($action, $data = [])
    {
        DB::table('system_logs')->insert([
            'level' => 'info',
            'message' => "Acción de auditoría: {$action}",
            'module' => 'audit',
            'context' => json_encode($data),
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Exportar logs del sistema
     */
    private function exportSystemLogs($dateFrom, $dateTo)
    {
        $logs = DB::table('system_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "system_logs_{$dateFrom}_to_{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nivel', 'Mensaje', 'Módulo', 'Contexto', 'Usuario ID', 'IP', 'User Agent', 'Fecha']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->level,
                    $log->message,
                    $log->module,
                    $log->context,
                    $log->user_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar actividad de usuarios
     */
    private function exportUserActivity($dateFrom, $dateTo)
    {
        $activities = DB::table('user_activity_logs')
            ->leftJoin('users', 'user_activity_logs.user_id', '=', 'users.id')
            ->select('user_activity_logs.*', 'users.name as user_name', 'users.email as user_email')
            ->whereBetween('user_activity_logs.created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('user_activity_logs.created_at', 'desc')
            ->get();

        $filename = "user_activity_{$dateFrom}_to_{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Usuario', 'Email', 'Acción', 'Descripción', 'Datos', 'IP', 'User Agent', 'Fecha']);

            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->user_name,
                    $activity->user_email,
                    $activity->action,
                    $activity->description,
                    $activity->data,
                    $activity->ip_address,
                    $activity->user_agent,
                    $activity->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar actividad de empresas
     */
    private function exportCompanyActivity($dateFrom, $dateTo)
    {
        $activities = DB::table('company_activity_logs')
            ->leftJoin('empresas', 'company_activity_logs.company_id', '=', 'empresas.id')
            ->select('company_activity_logs.*', 'empresas.nombre as company_name', 'empresas.email as company_email')
            ->whereBetween('company_activity_logs.created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('company_activity_logs.created_at', 'desc')
            ->get();

        $filename = "company_activity_{$dateFrom}_to_{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Empresa', 'Email', 'Acción', 'Descripción', 'Datos', 'IP', 'User Agent', 'Fecha']);

            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->company_name,
                    $activity->company_email,
                    $activity->action,
                    $activity->description,
                    $activity->data,
                    $activity->ip_address,
                    $activity->user_agent,
                    $activity->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar logs de seguridad
     */
    private function exportSecurityLogs($dateFrom, $dateTo)
    {
        $logs = DB::table('security_logs')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "security_logs_{$dateFrom}_to_{$dateTo}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Tipo', 'Descripción', 'Datos', 'Usuario ID', 'IP', 'User Agent', 'Fecha']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->type,
                    $log->description,
                    $log->data,
                    $log->user_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}