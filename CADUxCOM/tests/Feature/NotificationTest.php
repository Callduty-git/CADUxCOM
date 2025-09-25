<?php

use App\Models\User;
use App\Models\Notification;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Producto;

it('muestra las notificaciones del usuario autenticado', function () {
    $user = User::factory()->create();
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    Notification::factory()->create([
        'user_id' => $user->id,
        'empresa_id' => $empresa->Id_Empresa,
        'producto_id' => $producto->Id_Producto,
        'is_read' => false,
    ]);
    $this->actingAs($user);
    $response = $this->get('/notificaciones');
    $response->assertStatus(200);
    $response->assertSee('Notificaciones');
});

it('permite marcar una notificación como leída', function () {
    $user = User::factory()->create();
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    $notification = Notification::factory()->create([
        'user_id' => $user->id,
        'empresa_id' => $empresa->Id_Empresa,
        'producto_id' => $producto->Id_Producto,
        'is_read' => false,
    ]);
    $this->actingAs($user);
    $response = $this->patch("/notificaciones/{$notification->id}/read");
    $response->assertStatus(302); // Redirige de vuelta
    $notification->refresh();
    expect($notification->is_read)->toBeTrue();
});

it('permite eliminar una notificación', function () {
    $user = User::factory()->create();
    $empresa = Empresa::factory()->create();
    $categoria = Categoria::factory()->create();
    $subcategoria = Subcategoria::factory()->create(['Id_Categoria' => $categoria->Id_Categoria]);
    $producto = Producto::factory()->create([
        'Id_Empresa' => $empresa->Id_Empresa,
        'Id_Subcategoria' => $subcategoria->Id_Subcategoria,
    ]);
    $notification = Notification::factory()->create([
        'user_id' => $user->id,
        'empresa_id' => $empresa->Id_Empresa,
        'producto_id' => $producto->Id_Producto,
    ]);
    $this->actingAs($user);
    $response = $this->delete("/notificaciones/{$notification->id}");
    $response->assertStatus(302);
    $this->assertDatabaseMissing('notifications', [
        'id' => $notification->id,
    ]);
});
