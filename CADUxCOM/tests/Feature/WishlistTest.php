<?php

use App\Models\User;
use App\Models\Wishlist;
use App\Models\Producto;
use App\Models\Empresa;
use App\Models\Subcategoria;
use App\Models\Categoria;

it('permite agregar un producto a la wishlist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);

    $response = $this->post('/wishlist/add', [
        'product_id' => $producto->Id_Producto,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('wishlists', [
        'user_id' => $user->id,
        'product_id' => $producto->Id_Producto,
    ]);
});

it('permite limpiar la wishlist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    Wishlist::create(['user_id' => $user->id, 'product_id' => $producto->Id_Producto]);

    $response = $this->delete('/wishlist/clear');
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseMissing('wishlists', [
        'user_id' => $user->id,
    ]);
});

it('devuelve el contador correcto de wishlist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto1 = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    $producto2 = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    Wishlist::create(['user_id' => $user->id, 'product_id' => $producto1->Id_Producto]);
    Wishlist::create(['user_id' => $user->id, 'product_id' => $producto2->Id_Producto]);

    $response = $this->get('/wishlist/count');
    $response->assertStatus(200);
    $response->assertJson(['count' => 2]);
});
