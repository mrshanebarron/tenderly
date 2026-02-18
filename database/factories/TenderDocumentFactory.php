<?php

namespace Database\Factories;

use App\Models\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tender_id' => Tender::factory(),
            'filename' => 'tender-documents/1/' . fake()->slug(3) . '.pdf',
            'original_name' => fake()->words(3, true) . '.pdf',
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(102400, 5242880),
            'processing_status' => 'completed',
            'extracted_text' => fake()->paragraph(),
        ];
    }
}
