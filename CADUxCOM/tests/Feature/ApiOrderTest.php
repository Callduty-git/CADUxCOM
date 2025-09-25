<?php

use App\Models\User;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Order;
use Laravel\Sanctum\Sanctum;

it('API: protege órdenes para usuarios no autenticados', function () {
    $response = $this->getJson('/api/orders');
    $this->assertTrue(in_array($response->status(), [401, 404]));
});

it('API: permite listar órdenes autenticado', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    $order = Order::factory()->create(['user_id' => $user->id]);
    $response = $this->getJson('/api/orders');
    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => $order->id]);
});

it('API: permite ver detalle de orden autenticado', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    $order = Order::factory()->create(['user_id' => $user->id]);
    $response = $this->getJson('/api/orders/' . $order->id);
    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => $order->id]);
});
