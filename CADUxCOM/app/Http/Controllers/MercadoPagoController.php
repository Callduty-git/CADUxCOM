<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\Producto;

class MercadoPagoController extends Controller
{
    public function brick()
    {
        return view('payments.mercadopago');
    }

    public function createPreference(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Carrito vacío'], 400);
        }

        $accessToken = config('services.mercadopago.access_token');
        if (!$accessToken) {
            return response()->json(['error' => 'Credenciales de Mercado Pago faltantes'], 500);
        }

        $items = [];
        $currency = 'COP';

        foreach ($cart as $productId => $row) {
            $product = \App\Models\Producto::find($productId);
            if (!$product) {
                continue;
            }
            $qty = max(1, (int)($row['quantity'] ?? 1));
            $unitPrice = (float)($product->Precio ?? 0);

            $items[] = [
                'title' => $product->Nombre ?? 'Producto',
                'quantity' => $qty,
                'currency_id' => $currency,
                'unit_price' => round($unitPrice, 2),
            ];
        }

        if (empty($items)) {
            return response()->json(['error' => 'No hay productos válidos en el carrito'], 400);
        }

        $payload = [
            'items' => $items,
            'back_urls' => [
                'success' => route('payments.mercadopago.callback', ['result' => 'success']),
                'failure' => route('payments.mercadopago.callback', ['result' => 'failure']),
                'pending' => route('payments.mercadopago.callback', ['result' => 'pending']),
            ],
            // 'auto_return' => 'approved',
            'external_reference' => 'USER-' . (\Illuminate\Support\Facades\Auth::id() ?: 'guest') . '-' . now()->timestamp,
            'notification_url' => route('payments.mercadopago.webhook'),
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->acceptJson()
                ->post('https://api.mercadopago.com/checkout/preferences', $payload);

            if (!$response->successful()) {
                \Illuminate\Support\Facades\Log::error('MercadoPago preference error (createPreference)', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json([
                    'error' => 'No se pudo crear la preferencia',
                    'status' => $response->status(),
                ], 500);
            }

            $pref = $response->json();
            return response()->json([
                'id' => $pref['id'] ?? null,
                'init_point' => $pref['init_point'] ?? ($pref['sandbox_init_point'] ?? null),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('MercadoPago createPreference exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Error inesperado creando la preferencia'], 500);
        }
    }
    public function start(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $accessToken = config('services.mercadopago.access_token');
        if (!$accessToken) {
            return redirect()->route('cart.index')->with('error', 'Faltan credenciales de Mercado Pago.');
        }

        $items = [];
        $currency = 'COP';

        foreach ($cart as $productId => $row) {
            $product = Producto::find($productId);
            if (!$product) {
                continue;
            }
            $qty = max(1, (int)($row['quantity'] ?? 1));
            $unitPrice = (float)($product->Precio ?? 0);

            $items[] = [
                'title' => $product->Nombre ?? 'Producto',
                'quantity' => $qty,
                'currency_id' => $currency,
                'unit_price' => round($unitPrice, 2),
            ];
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'No se pudieron cargar los productos del carrito.');
        }

        $payload = [
            'items' => $items,
            'back_urls' => [
                'success' => route('payments.mercadopago.callback', ['result' => 'success']),
                'failure' => route('payments.mercadopago.callback', ['result' => 'failure']),
                'pending' => route('payments.mercadopago.callback', ['result' => 'pending']),
            ],
            // 'auto_return' => 'approved',
            'external_reference' => 'USER-' . (Auth::id() ?: 'guest') . '-' . now()->timestamp,
            'notification_url' => route('payments.mercadopago.webhook'),
        ];

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->post('https://api.mercadopago.com/checkout/preferences', $payload);

            if (!$response->successful()) {
                Log::error('MercadoPago preference error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return redirect()->route('cart.index')->with('error', 'No se pudo iniciar el pago con Mercado Pago.');
            }

            $pref = $response->json();
            $initPoint = $pref['init_point'] ?? $pref['sandbox_init_point'] ?? null;
            if (!$initPoint) {
                return redirect()->route('cart.index')->with('error', 'Respuesta inv�lida de Mercado Pago.');
            }

            Session::put('mp_preference_id', $pref['id'] ?? null);
            return redirect()->away($initPoint);
        } catch (\Throwable $e) {
            Log::error('MercadoPago start exception', ['message' => $e->getMessage()]);
            return redirect()->route('cart.index')->with('error', 'Ocurri� un error iniciando el pago.');
        }
    }

    public function callback(Request $request)
    {
        $result = $request->query('result');
        if ($result === 'success') {
            Session::forget('cart');
            return redirect()->route('home')->with('success', 'Pago aprobado en Mercado Pago. �Gracias por tu compra!');
        }
        if ($result === 'pending') {
            return redirect()->route('cart.index')->with('info', 'Tu pago qued� pendiente en Mercado Pago.');
        }
        return redirect()->route('cart.index')->with('error', 'Pago cancelado o fallido en Mercado Pago.');
    }

    public function webhook(Request $request)
    {
        Log::info('MercadoPago webhook', ['payload' => $request->all()]);
        return response()->json(['received' => true]);
    }
}
