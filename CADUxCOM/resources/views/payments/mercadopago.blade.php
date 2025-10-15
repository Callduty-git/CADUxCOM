<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pago con Mercado Pago - CADUxCOM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-new.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="preconnect" href="https://sdk.mercadopago.com" />
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <style>
        .mp-wrapper { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        .mp-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; }
        .mp-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,.06); padding: 20px; }
        .mp-title { font-size: 1.25rem; font-weight: 700; margin: 0 0 12px; }
        .mp-subtitle { color: #6b7280; margin-bottom: 16px; }
        .mp-summary-item { display: flex; justify-content: space-between; margin: 8px 0; }
        .mp-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem; margin-top: 12px; }
        .mp-divider { height: 1px; background: #e5e7eb; margin: 12px 0; border: 0; }
        .mp-loading { display: flex; align-items: center; gap: 8px; color: #374151; }
        .mp-spinner { width: 16px; height: 16px; border: 3px solid #e5e7eb; border-top-color: #00a650; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .mp-err { color: #b91c1c; background: #fee2e2; border: 1px solid #fecaca; padding: 10px 12px; border-radius: 8px; }
        .mp-init-link { display: inline-block; margin-top: 12px; padding: 10px 14px; background: #00a650; color: #fff; border-radius: 8px; text-decoration: none; }
        @media (max-width: 900px){ .mp-grid{ grid-template-columns: 1fr; } }
    </style>
</head>
<body class="bg-gray-50">
    <x-header />
    <x-navbar-new />

    <main class="mp-wrapper">
        <h1 class="mp-title">Pagar con Mercado Pago</h1>
        <p class="mp-subtitle">Generaremos una preferencia y podrás pagar con tu cuenta o tarjeta.</p>

        <div class="mp-grid">
            <section class="mp-card">
                <h2 class="mp-title" style="font-size:1.1rem">Elige cómo pagar</h2>
                <div id="mp-status" class="mp-loading" aria-live="polite">
                    <span class="mp-spinner"></span>
                    Preparando Mercado Pago...
                </div>
                <div id="mp-wallet-brick" style="margin-top:14px;"></div>
                <a id="mp-init-point" href="#" class="mp-init-link" style="display:none" rel="noopener">Pagar ahora</a>
                <div id="mp-error" class="mp-err" style="display:none;margin-top:12px;"></div>
            </section>

            <aside class="mp-card">
                <h2 class="mp-title" style="font-size:1.1rem">Detalles del pago</h2>
                @php
                    $cart = session('cart', []);
                    $subtotal = 0;
                @endphp
                @if(!empty($cart))
                    @foreach($cart as $pid => $row)
                        @php
                            $product = \App\Models\Producto::find($pid);
                            if(!$product) continue;
                            $qty = max(1, (int)($row['quantity'] ?? 1));
                            $price = (float)($product->Precio ?? 0);
                            $line = $qty * $price;
                            $subtotal += $line;
                        @endphp
                        <div class="mp-summary-item">
                            <span>{{ $product->Nombre }} × {{ $qty }}</span>
                            <span>${{ number_format($line, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <hr class="mp-divider" />
                    <div class="mp-total">
                        <span>Total</span>
                        <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                @else
                    <p>No tienes productos en el carrito.</p>
                @endif
            </aside>
        </div>
    </main>

    <x-footer />

    <script>
    (function(){
      const statusEl = document.getElementById('mp-status');
      const errorEl = document.getElementById('mp-error');
      const initLink = document.getElementById('mp-init-point');

      async function createPreference(){
        try{
          const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const res = await fetch('/payments/mercadopago/preference', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
          });
          const data = await res.json();
          if(!res.ok || !data || (!data.id && !data.init_point)){
            throw new Error(data && data.error ? data.error : 'No se pudo crear la preferencia');
          }
          return data;
        } catch (e){ throw e; }
      }

      function showError(msg){
        if(statusEl) statusEl.style.display = 'none';
        if(errorEl){ errorEl.style.display = 'block'; errorEl.textContent = msg; }
      }

      async function init(){
        try{
          const pref = await createPreference();
          if(statusEl) statusEl.style.display = 'none';

          // Fallback: enlace directo al init_point
          if (pref.init_point) {
            initLink.href = pref.init_point;
            initLink.style.display = 'inline-block';
          }

          // Wallet Brick
          const mp = new MercadoPago('{{ config('services.mercadopago.public_key') }}', { locale: 'es-CO' });
          const bricksBuilder = mp.bricks();
          await bricksBuilder.create('wallet', 'mp-wallet-brick', {
            initialization: {
              preferenceId: pref.id,
            },
            customization: {
              texts: { valueProp: 'smart_option' }
            }
          });
        } catch (e){
          showError(e.message || 'Error iniciando Mercado Pago');
        }
      }

      init();
    })();
    </script>
</body>
</html>