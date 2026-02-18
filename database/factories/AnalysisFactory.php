<?php

namespace Database\Factories;

use App\Models\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalysisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'type' => 'consensus',
            'title' => 'Consensus Points Analysis',
            'content' => [
                'summary' => fake()->paragraph(),
                'points' => [
                    ['area' => 'Architecture', 'agreement_level' => 90, 'detail' => fake()->sentence()],
                ],
            ],
            'confidence' => fake()->randomFloat(2, 0.65, 0.95),
        ];
    }
}
