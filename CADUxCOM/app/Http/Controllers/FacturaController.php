<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\LogEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class FacturaController extends Controller
{
    /**
     * Lista todas las facturas de la empresa del usuario autenticado y los logs agrupados.
     */
    public function index()
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) {
            abort(403, 'Acceso no autorizado.');
        }
        $empresaId = $empresa->Id_Empresa;

        $facturas = Factura::with('empresa')
            ->where('empresa_id', $empresaId)
            ->orderBy('fecha_emision', 'desc')
            ->get();

        // Obtener todos los logs de la empresa autenticada, ordenados por fecha de creación descendente
        $rawLogs = LogEmpresa::where('empresa_id', $empresaId) // 👈 ESTO ES LO QUE YA FILTRA POR EMPRESA
                               ->orderBy('hora', 'desc')
                               ->get();
        
        // Contar registros actuales
        $totalLogs = $rawLogs->count();
        $maxLogs = 50;
        
        // Obtener todos los productos de la empresa para poder mostrar sus imágenes
        $productos = \App\Models\Producto::where('Id_Empresa', $empresaId)->get();

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
            'facturas' => $facturas,
            'logs' => $groupedLogs,
            'productos' => $productos,
            'totalLogs' => $totalLogs,
            'maxLogs' => $maxLogs,
        ]);
    }

    public function create()
    {
        return view('facturas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_factura' => 'required|string|max:255',
            'fecha_emision'  => 'required|date',
            'total'          => 'required|numeric',
        ]);

        Factura::create([
            'numero_factura' => $request->numero_factura,
            'fecha_emision'  => $request->fecha_emision,
            'total'          => $request->total,
            'empresa_id'     => Auth::guard('empresa')->user()->Id_Empresa,
        ]);

        return redirect()->route('empresa.facturas')
            ->with('success', 'Factura creada con éxito.');
    }

    public function show(Factura $factura)
    {
        // Asegúrate de que la factura pertenezca a la empresa autenticada
        $empresaId = Auth::guard('empresa')->user()->Id_Empresa;
        if ($factura->empresa_id !== $empresaId) {
            abort(403, 'Acceso no autorizado.'); // O redirecciona a una página de error
        }
        return view('facturas.show', compact('factura'));
    }

    public function edit(Factura $factura)
    {
        // Asegúrate de que la factura pertenezca a la empresa autenticada
        $empresaId = Auth::guard('empresa')->user()->Id_Empresa;
        if ($factura->empresa_id !== $empresaId) {
            abort(403, 'Acceso no autorizado.');
        }
        return view('facturas.edit', compact('factura'));
    }

    public function update(Request $request, Factura $factura)
    {
        // Asegúrate de que la factura pertenezca a la empresa autenticada antes de actualizar
        $empresaId = Auth::guard('empresa')->user()->Id_Empresa;
        if ($factura->empresa_id !== $empresaId) {
            abort(403, 'Acceso no autorizado.');
        }

        $request->validate([
            'numero_factura' => 'required|string|max:255',
            'fecha_emision'  => 'required|date',
            'total'          => 'required|numeric',
        ]);

        $factura->update($request->only(['numero_factura', 'fecha_emision', 'total']));

        return redirect()->route('empresa.facturas')
            ->with('success', 'Factura actualizada con éxito.');
    }

    public function destroy(Factura $factura)
    {
        // Asegúrate de que la factura pertenezca a la empresa autenticada antes de eliminar
        $empresaId = Auth::guard('empresa')->user()->Id_Empresa;
        if ($factura->empresa_id !== $empresaId) {
            abort(403, 'Acceso no autorizado.');
        }
        $factura->delete();

        return redirect()->route('empresa.facturas')
            ->with('success', 'Factura eliminada con éxito.');
    }

    /**
     * Limpiar todos los logs de la empresa
     */
    public function clearLogs()
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) {
            abort(403, 'Acceso no autorizado.');
        }

        LogEmpresa::where('empresa_id', $empresa->Id_Empresa)->delete();

        return redirect()->route('empresa.facturas')
            ->with('success', 'Todos los logs han sido eliminados.');
    }
}