<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['expiry_alert', 'discount_available', 'new_product']),
            'title' => $this->faker->sentence(3),
            'message' => $this->faker->sentence(8),
            'data' => [],
            'user_id' => User::factory(),
            'empresa_id' => Empresa::factory(),
            'producto_id' => Producto::factory(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'channel' => $this->faker->randomElement(['in_app', 'email']),
            'is_read' => false,
            'is_sent' => false,
            'scheduled_at' => null,
            'sent_at' => null,
            'read_at' => null,
        ];
    }
}
