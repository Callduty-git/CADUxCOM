<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogEmpresa;
use Carbon\Carbon;

class EmpresaDashboardController extends Controller
{
    /**
     * Muestra el dashboard de la empresa con sus productos.
     */
    public function index()
    {
        $empresa = Auth::guard('empresa')->user();
        $productos = \App\Models\Producto::where('Id_Empresa', $empresa->Id_Empresa)->get();

        return view('dashboard', compact('empresa', 'productos'));
    }

    /**
     * Muestra y filtra los registros (logs) de la empresa.
     */
    public function facturas(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) abort(403, 'Acceso no autorizado.');
        $empresaId = $empresa->Id_Empresa;

        $searchTerm = $request->get('search');
        $query = LogEmpresa::where('empresa_id', $empresaId);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('accion', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('descripcion', 'LIKE', "%{$searchTerm}%");

                $searchLower = strtolower(trim($searchTerm));

                if ($searchLower === 'hoy') {
                    $q->orWhereDate('hora', Carbon::today());
                } elseif ($searchLower === 'ayer') {
                    $q->orWhereDate('hora', Carbon::yesterday());
                } elseif (in_array($searchLower, ['esta semana', 'semana'])) {
                    $q->orWhereBetween('hora', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]);
                } elseif (in_array($searchLower, ['este mes', 'mes'])) {
                    $q->orWhereBetween('hora', [
                        Carbon::now()->startOfMonth(),
                        Carbon::now()->endOfMonth(),
                    ]);
                } elseif (in_array($searchLower, ['agregar', 'agregado', 'añadir', 'añadido'])) {
                    $q->orWhere('accion', 'LIKE', '%agregó%')
                        ->orWhere('accion', 'LIKE', '%agregar%')
                        ->orWhere('accion', 'LIKE', '%añadir%')
                        ->orWhere('accion', 'LIKE', '%crear%')
                        ->orWhere('accion', 'LIKE', '%subir%');
                } elseif (in_array($searchLower, ['eliminar', 'eliminado', 'borrar', 'borrado'])) {
                    $q->orWhere('accion', 'LIKE', '%eliminó%')
                        ->orWhere('accion', 'LIKE', '%eliminar%')
                        ->orWhere('accion', 'LIKE', '%borrar%')
                        ->orWhere('accion', 'LIKE', '%delete%');
                }

                // Búsqueda por fecha exacta (dd/mm/yyyy o dd-mm-yyyy)
                if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $searchTerm, $matches)) {
                    $day = $matches[1];
                    $month = $matches[2];
                    $year = $matches[3];
                    $q->orWhereDate('hora', "{$year}-{$month}-{$day}");
                }

                // Búsqueda por nombre de día de la semana
                $diasSemana = [
                    'lunes' => 1, 'martes' => 2, 'miércoles' => 3, 'miercoles' => 3,
                    'jueves' => 4, 'viernes' => 5, 'sábado' => 6, 'sabado' => 6, 'domingo' => 0,
                ];

                if (isset($diasSemana[$searchLower])) {
                    $q->orWhereRaw('DAYOFWEEK(hora) = ?', [$diasSemana[$searchLower] + 1]);
                }
            });
        }

        $rawLogs = $query->orderBy('hora', 'desc')->get();
        $totalLogs = $rawLogs->count();
        $maxLogs = 50;

        $productos = \App\Models\Producto::where('Id_Empresa', $empresaId)->get();

        // Agrupar los logs por fechas (hoy, ayer, semana, mes, etc.)
        $groupedLogs = [];
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $lastWeek = Carbon::now()->subWeek()->startOfDay();
        $lastMonth = Carbon::now()->subMonth()->startOfDay();
        $currentGroup = '';

        foreach ($rawLogs as $log) {
            $logDate = Carbon::parse($log->hora)->startOfDay();

            if ($logDate->equalTo($today)) {
                $groupName = 'HOY';
            } elseif ($logDate->equalTo($yesterday)) {
                $groupName = 'AYER';
            } elseif ($logDate->greaterThanOrEqualTo($lastWeek) && $logDate->lt($yesterday)) {
                $groupName = 'ESTA SEMANA';
            } elseif ($logDate->greaterThanOrEqualTo($lastMonth) && $logDate->lt($lastWeek)) {
                $groupName = 'ESTE MES';
            } else {
                $groupName = $logDate->translatedFormat('F Y');
            }

            if ($groupName !== $currentGroup) {
                $groupedLogs[] = [
                    'type' => 'separator',
                    'text' => $groupName,
                ];
                $currentGroup = $groupName;
            }

            $groupedLogs[] = [
                'type' => 'log',
                'data' => $log,
            ];
        }

        return view('facturas.index', [
            'logs' => $groupedLogs,
            'productos' => $productos,
            'searchTerm' => $searchTerm,
            'totalLogs' => $totalLogs,
            'maxLogs' => $maxLogs,
        ]);
    }

    /**
     * Elimina todos los logs de la empresa autenticada.
     */
    public function clearLogs()
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) abort(403, 'Acceso no autorizado.');

        LogEmpresa::where('empresa_id', $empresa->Id_Empresa)->delete();

        return redirect()->route('empresa.facturas')
            ->with('success', 'Todos los logs han sido eliminados.');
    }

    /**
     * Alterna el estado del descuento progresivo para la empresa autenticada.
     */
    public function toggleProgressiveDiscount(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) abort(403, 'Acceso no autorizado.');

        // Alternar el flag de descuento progresivo
        $enabled = !$empresa->progressive_discount_enabled;

        \App\Models\Empresa::where('Id_Empresa', $empresa->Id_Empresa)
            ->update(['progressive_discount_enabled' => $enabled]);

        // Reflejar el nuevo estado en la instancia actual
        $empresa->progressive_discount_enabled = $enabled;

        return response()->json([
            'success' => true,
            'enabled' => (bool) $empresa->progressive_discount_enabled,
        ]);
    }
}
