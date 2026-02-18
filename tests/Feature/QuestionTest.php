<?php

namespace Tests\Feature;

use App\Models\Criterion;
use App\Models\Question;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Tender $tender;
    private Criterion $criterion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->tender = Tender::factory()->for($this->user)->create();
        $this->criterion = Criterion::factory()->for($this->tender)->create();
    }

    public function test_owner_can_add_question(): void
    {
        $this->actingAs($this->user)
            ->post(route('questions.store', $this->criterion), [
                'question_text' => 'How will you ensure GDPR compliance?',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('questions', [
            'criterion_id' => $this->criterion->id,
            'question_text' => 'How will you ensure GDPR compliance?',
            'source' => 'manual',
        ]);
    }

    public function test_question_requires_text(): void
    {
        $this->actingAs($this->user)
            ->post(route('questions.store', $this->criterion), ['question_text' => ''])
            ->assertSessionHasErrors('question_text');
    }

    public function test_question_accepts_valid_priority(): void
    {
        $this->actingAs($this->user)
            ->post(route('questions.store', $this->criterion), [
                'question_text' => 'Critical question?',
                'priority' => 'critical',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('questions', ['priority' => 'critical']);
    }

    public function test_question_rejects_invalid_priority(): void
    {
        $this->actingAs($this->user)
            ->post(route('questions.store', $this->criterion), [
                'question_text' => 'Test?',
                'priority' => 'urgent',
            ])
            ->assertSessionHasErrors('priority');
    }

    public function test_owner_can_update_question(): void
    {
        $question = Question::factory()->for($this->criterion)->create();

        $this->actingAs($this->user)
            ->put(route('questions.update', $question), [
                'question_text' => 'Updated question text?',
                'priority' => 'high',
            ])
            ->assertRedirect();

        $question->refresh();
        $this->assertEquals('Updated question text?', $question->question_text);
        $this->assertEquals('high', $question->priority);
    }

    public function test_owner_can_delete_question(): void
    {
        $question = Question::factory()->for($this->criterion)->create();

        $this->actingAs($this->user)
            ->delete(route('questions.destroy', $question))
            ->assertRedirect();

        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    public function test_ai_generates_questions_for_technical_criterion(): void
    {
        $criterion = Criterion::factory()->for($this->tender)->create(['name' => 'Technical Requirements']);

        $this->actingAs($this->user)
            ->post(route('questions.generate', $criterion))
            ->assertRedirect();

        $this->assertEquals(5, $criterion->questions()->count());
        $this->assertTrue($criterion->questions()->where('source', 'ai_generated')->exists());
    }

    public function test_ai_generates_questions_for_unknown_criterion(): void
    {
        $criterion = Criterion::factory()->for($this->tender)->create(['name' => 'Custom Criterion']);

        $this->actingAs($this->user)
            ->post(route('questions.generate', $criterion))
            ->assertRedirect();

        // Default template generates 5 questions
        $this->assertEquals(5, $criterion->questions()->count());
    }

    public function test_non_owner_cannot_manage_questions(): void
    {
        $other = User::factory()->create();
        $otherTender = Tender::factory()->for($other)->create();
        $otherCriterion = Criterion::factory()->for($otherTender)->create();
        $question = Question::factory()->for($otherCriterion)->create();

        $this->actingAs($this->user)
            ->post(route('questions.store', $otherCriterion), ['question_text' => 'Hacked?'])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->put(route('questions.update', $question), ['question_text' => 'Hacked?'])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->delete(route('questions.destroy', $question))
            ->assertForbidden();

        $this->actingAs($this->user)
            ->post(route('questions.generate', $otherCriterion))
            ->assertForbidden();
    }
}
