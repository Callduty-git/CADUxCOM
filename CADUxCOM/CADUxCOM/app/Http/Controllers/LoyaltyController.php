<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador LoyaltyController - Sistema de puntos de fidelidad
 */
class LoyaltyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $stats = LoyaltyPoint::getUserStats($user->id);
        $recentPoints = LoyaltyPoint::getUserHistory($user->id, 10);

        return view('loyalty.index', compact('user', 'stats', 'recentPoints'));
    }

    public function history()
    {
        $user = Auth::user();
        $points = LoyaltyPoint::byUser($user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('loyalty.history', compact('user', 'points'));
    }

    public function getStats()
    {
        $user = Auth::user();
        $stats = LoyaltyPoint::getUserStats($user->id);

        return response()->json($stats);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:100',
            'description' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $pointsToRedeem = $request->points;

        // Verificar que el usuario tiene suficientes puntos
        $availablePoints = LoyaltyPoint::getTotalActivePoints($user->id);
        
        if ($availablePoints < $pointsToRedeem) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes suficientes puntos disponibles.',
            ], 400);
        }

        // Crear transacción de canje
        $loyaltyPoint = LoyaltyPoint::redeemForDiscount(
            $user->id,
            $pointsToRedeem,
            $request->description
        );

        return response()->json([
            'success' => true,
            'message' => 'Puntos canjeados exitosamente.',
            'points' => $loyaltyPoint,
        ]);
    }
}