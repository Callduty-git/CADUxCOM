<?php

use App\Models\User;
use App\Models\Producto;
use App\Models\Empresa;
use App\Models\Subcategoria;
use App\Models\Categoria;
use Laravel\Sanctum\Sanctum;

it('API: permite obtener la wishlist autenticado', function () {
    $user = User::factory()->create();
    \Laravel\Sanctum\Sanctum::actingAs($user, ['*']);
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    $user->wishlists()->attach($producto->Id_Producto);

    $response = $this->getJson('/api/wishlist');
    $response->assertStatus(200);
    $response->assertJsonFragment(['product_id' => $producto->Id_Producto]);
});

it('API: protege wishlist para usuarios no autenticados', function () {
    $response = $this->getJson('/api/wishlist');
    $this->assertTrue(in_array($response->status(), [401, 404]));
});

it('API: permite agregar producto a wishlist', function () {
    $user = User::factory()->create();
    \Laravel\Sanctum\Sanctum::actingAs($user, ['*']);
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    $response = $this->postJson('/api/wishlist', [
        'product_id' => $producto->Id_Producto,
    ]);
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $producto->Id_Producto,
    ]);
});
