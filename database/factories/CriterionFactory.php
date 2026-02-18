<?php

namespace Database\Factories;

use App\Models\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;

class CriterionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'parent_id' => null,
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'weight' => fake()->numberBetween(10, 40),
            'sort_order' => 1,
        ];
    }
}
