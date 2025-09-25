<?php

use App\Models\User;
use App\Models\Producto;
use App\Models\Empresa;
use App\Models\Subcategoria;
use App\Models\Categoria;
use Laravel\Sanctum\Sanctum;

it('API: permite listar productos autenticado', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    $response = $this->getJson('/api/productos');
    $response->assertStatus(200);
    $response->assertJsonFragment(['Id_Producto' => $producto->Id_Producto]);
});

it('API: protege productos para usuarios no autenticados', function () {
    $response = $this->getJson('/api/productos');
    $this->assertTrue(in_array($response->status(), [401, 404]));
});

it('API: permite ver detalle de producto autenticado', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    $response = $this->getJson('/api/productos/' . $producto->Id_Producto);
    $response->assertStatus(200);
    $response->assertJsonFragment(['Id_Producto' => $producto->Id_Producto]);
});
