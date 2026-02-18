<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'objectives' => fake()->paragraph(),
            'focus_themes' => fake()->sentence(),
            'context_notes' => fake()->paragraph(),
            'deadline' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'status' => 'draft',
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function reviewing(): static
    {
        return $this->state(fn () => ['status' => 'reviewing']);
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => 'completed']);
    }
}
