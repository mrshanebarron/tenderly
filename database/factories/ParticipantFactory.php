<?php

namespace Database\Factories;

use App\Models\Participant;
use App\Models\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->jobTitle(),
            'token' => Participant::generateToken(),
            'status' => 'invited',
            'invited_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status' => 'active',
            'last_active_at' => now(),
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn () => [
            'status' => 'submitted',
            'last_active_at' => now(),
            'submitted_at' => now(),
        ]);
    }

    public function locked(): static
    {
        return $this->state(fn () => ['status' => 'locked']);
    }
}
