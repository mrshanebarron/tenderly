<?php

namespace Tests\Feature;

use App\Models\Criterion;
use App\Models\FollowUp;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Response as TenderResponse;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RespondTest extends TestCase
{
    use RefreshDatabase;

    private Tender $tender;
    private Participant $participant;
    private Question $question;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->tender = Tender::factory()->for($user)->active()->create();
        $criterion = Criterion::factory()->for($this->tender)->create();
        $this->question = Question::factory()->for($criterion)->create();
        $this->participant = Participant::factory()->for($this->tender)->create(['status' => 'invited']);
    }

    public function test_participant_can_access_response_page_via_token(): void
    {
        $this->get(route('respond.show', $this->participant->token))
            ->assertOk()
            ->assertViewIs('respond.show');
    }

    public function test_invalid_token_returns_404(): void
    {
        $this->get(route('respond.show', 'invalid-token-that-does-not-exist'))
            ->assertNotFound();
    }

    public function test_first_access_transitions_invited_to_active(): void
    {
        $this->assertEquals('invited', $this->participant->status);

        $this->get(route('respond.show', $this->participant->token));

        $this->assertEquals('active', $this->participant->fresh()->status);
        $this->assertNotNull($this->participant->fresh()->last_active_at);
    }

    public function test_locked_participant_sees_locked_view(): void
    {
        $this->participant->update(['status' => 'locked']);

        $this->get(route('respond.show', $this->participant->token))
            ->assertOk()
            ->assertViewIs('respond.locked');
    }

    public function test_participant_can_save_responses(): void
    {
        $this->participant->update(['status' => 'active']);

        $this->post(route('respond.save', $this->participant->token), [
            'answers' => [
                $this->question->id => 'Our approach uses microservices architecture for scalability.',
            ],
        ])->assertRedirect();

        $this->assertDatabaseHas('responses', [
            'participant_id' => $this->participant->id,
            'question_id' => $this->question->id,
        ]);
    }

    public function test_save_calculates_completeness_score(): void
    {
        $this->participant->update(['status' => 'active']);

        $shortAnswer = 'Brief answer.';
        $this->post(route('respond.save', $this->participant->token), [
            'answers' => [$this->question->id => $shortAnswer],
        ]);

        $response = TenderResponse::where('participant_id', $this->participant->id)->first();
        $expected = min(1, strlen(trim($shortAnswer)) / 200);
        $this->assertEquals($expected, (float) $response->completeness_score);
    }

    public function test_locked_participant_cannot_save(): void
    {
        $this->participant->update(['status' => 'locked']);

        $this->post(route('respond.save', $this->participant->token), [
            'answers' => [$this->question->id => 'Should fail.'],
        ])->assertForbidden();
    }

    public function test_submitted_participant_cannot_save(): void
    {
        $this->participant->update(['status' => 'submitted', 'submitted_at' => now()]);

        $this->post(route('respond.save', $this->participant->token), [
            'answers' => [$this->question->id => 'Should fail.'],
        ])->assertForbidden();
    }

    public function test_participant_can_submit(): void
    {
        $this->participant->update(['status' => 'active']);

        $this->post(route('respond.submit', $this->participant->token))
            ->assertOk()
            ->assertViewIs('respond.submitted');

        $this->assertEquals('submitted', $this->participant->fresh()->status);
        $this->assertNotNull($this->participant->fresh()->submitted_at);
    }

    public function test_submit_generates_follow_ups_for_brief_responses(): void
    {
        $this->participant->update(['status' => 'active']);

        // Create 3 brief responses (< 100 chars)
        $criterion = $this->question->criterion;
        $questions = Question::factory()->for($criterion)->count(2)->create();
        $allQuestions = collect([$this->question])->merge($questions);

        foreach ($allQuestions as $q) {
            TenderResponse::create([
                'participant_id' => $this->participant->id,
                'question_id' => $q->id,
                'answer_text' => 'Short.',
                'completeness_score' => 0.03,
            ]);
        }

        $this->post(route('respond.submit', $this->participant->token));

        $this->assertEquals(3, FollowUp::count());
    }

    public function test_locked_participant_cannot_submit(): void
    {
        $this->participant->update(['status' => 'locked']);

        $this->post(route('respond.submit', $this->participant->token))
            ->assertForbidden();
    }
}
