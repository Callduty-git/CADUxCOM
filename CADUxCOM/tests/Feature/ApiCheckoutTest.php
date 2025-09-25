<?php

use App\Models\User;
use App\Models\Empresa;
use App\Models\Producto;
use Laravel\Sanctum\Sanctum;

it('API: protege checkout para usuarios no autenticados', function () {
    $response = $this->postJson('/api/checkout', []);
    $this->assertTrue(in_array($response->status(), [401, 404]));
});

it('API: permite realizar checkout autenticado', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    $empresa = Empresa::factory()->create();
    $producto = Producto::factory()->create(['Id_Empresa' => $empresa->Id_Empresa]);
    $payload = [
        'items' => [
            [
                'product_id' => $producto->Id_Producto,
                'quantity' => 1
            ]
        ],
        'address' => 'Calle Falsa 123',
        'payment_method' => 'test',
    ];
    $response = $this->postJson('/api/checkout', $payload);
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
});
