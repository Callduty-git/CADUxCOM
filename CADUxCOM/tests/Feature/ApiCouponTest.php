<?php

use App\Models\User;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Coupon;
use Laravel\Sanctum\Sanctum;

it('API: protege cupones para usuarios no autenticados', function () {
    $response = $this->getJson('/api/coupons');
    $this->assertTrue(in_array($response->status(), [401, 404]));
});

it('API: permite listar cupones autenticado', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    $coupon = Coupon::factory()->create();
    $response = $this->getJson('/api/coupons');
    $response->assertStatus(200);
    $response->assertJsonFragment(['code' => $coupon->code]);
});

it('API: permite canjear un cupón autenticado', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    $coupon = Coupon::factory()->create(['code' => 'TESTCODE']);
    $response = $this->postJson('/api/coupons/redeem', ['code' => 'TESTCODE']);
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
});
