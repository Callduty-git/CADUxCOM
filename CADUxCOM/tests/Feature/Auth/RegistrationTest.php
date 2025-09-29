<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');
    $response->assertStatus(200);
});

test('new users can register (con role)', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'usuario', // Incluido para el caso de roles
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/dashboard'); // Redirección esperada
});

test('new users can register (sin role)', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test2@example.com', // Cambiado para evitar duplicado
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false)); // Otra forma de redirección
});
