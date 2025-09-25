<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper($this->faker->unique()->bothify('######')),
            'user_id' => User::factory(),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->phoneNumber(),
            'shipping_address' => $this->faker->address(),
            'shipping_city' => $this->faker->city(),
            'shipping_state' => $this->faker->state(),
            'shipping_postal_code' => $this->faker->postcode(),
            'shipping_country' => 'Colombia',
            'billing_address' => $this->faker->address(),
            'billing_city' => $this->faker->city(),
            'billing_state' => $this->faker->state(),
            'billing_postal_code' => $this->faker->postcode(),
            'billing_country' => 'Colombia',
            'subtotal' => $this->faker->randomFloat(2, 10, 500),
            'tax_amount' => 0,
            'shipping_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'coupon_code' => null,
            'coupon_discount' => 0,
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'payment_reference' => null,
            'paid_at' => null,
            'tracking_number' => null,
            'shipped_at' => null,
            'delivered_at' => null,
            'notes' => null,
            'admin_notes' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
