<?php

namespace Database\Factories;

use App\Models\Response;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowUpFactory extends Factory
{
    protected $model = \App\Models\FollowUp::class;

    public function definition(): array
    {
        return [
            'response_id' => Response::factory(),
            'role' => 'ai',
            'message' => fake()->paragraph(),
            'sequence' => 1,
        ];
    }
}
