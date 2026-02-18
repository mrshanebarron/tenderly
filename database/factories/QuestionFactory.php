<?php

namespace Database\Factories;

use App\Models\Criterion;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'criterion_id' => Criterion::factory(),
            'question_text' => fake()->sentence() . '?',
            'priority' => 'normal',
            'source' => 'manual',
            'sort_order' => 1,
            'depth_level' => 0,
        ];
    }

    public function critical(): static
    {
        return $this->state(fn () => ['priority' => 'critical']);
    }

    public function high(): static
    {
        return $this->state(fn () => ['priority' => 'high']);
    }

    public function aiGenerated(): static
    {
        return $this->state(fn () => ['source' => 'ai_generated']);
    }
}
