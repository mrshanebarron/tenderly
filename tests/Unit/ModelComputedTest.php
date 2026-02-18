<?php

namespace Tests\Unit;

use App\Models\Criterion;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Response as TenderResponse;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelComputedTest extends TestCase
{
    use RefreshDatabase;

    public function test_tender_progress_with_no_participants(): void
    {
        $tender = Tender::factory()->create();
        $this->assertEquals(0, $tender->progress);
    }

    public function test_tender_progress_with_submitted_participants(): void
    {
        $tender = Tender::factory()->create();
        Participant::factory()->for($tender)->submitted()->count(2)->create();
        Participant::factory()->for($tender)->active()->create();

        // 2 submitted out of 3 = 67%
        $this->assertEquals(67, $tender->progress);
    }

    public function test_tender_progress_all_submitted(): void
    {
        $tender = Tender::factory()->create();
        Participant::factory()->for($tender)->submitted()->count(3)->create();

        $this->assertEquals(100, $tender->progress);
    }

    public function test_participant_completion_with_no_questions(): void
    {
        $tender = Tender::factory()->create();
        $participant = Participant::factory()->for($tender)->create();

        $this->assertEquals(0, $participant->completion_percent);
    }

    public function test_participant_completion_with_partial_answers(): void
    {
        $tender = Tender::factory()->create();
        $criterion = Criterion::factory()->for($tender)->create();
        $q1 = Question::factory()->for($criterion)->create();
        $q2 = Question::factory()->for($criterion)->create();

        $participant = Participant::factory()->for($tender)->active()->create();

        TenderResponse::create([
            'participant_id' => $participant->id,
            'question_id' => $q1->id,
            'answer_text' => 'Answered.',
            'completeness_score' => 0.5,
        ]);

        // 1 answered out of 2 = 50%
        $this->assertEquals(50, $participant->completion_percent);
    }

    public function test_tender_has_many_relationships(): void
    {
        $user = User::factory()->create();
        $tender = Tender::factory()->for($user)->create();

        $this->assertTrue($tender->user->is($user));
        $this->assertCount(0, $tender->criteria);
        $this->assertCount(0, $tender->participants);
        $this->assertCount(0, $tender->documents);
        $this->assertCount(0, $tender->analyses);
    }
}
