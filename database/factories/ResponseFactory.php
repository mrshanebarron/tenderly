<?php

namespace Database\Factories;

use App\Models\Participant;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResponseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'participant_id' => Participant::factory(),
            'question_id' => Question::factory(),
            'answer_text' => fake()->paragraph(),
            'completeness_score' => fake()->randomFloat(2, 0.5, 1.0),
        ];
    }
}
