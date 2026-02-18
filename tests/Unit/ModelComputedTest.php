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

    public function test_criterion_parent_child_hierarchy(): void
    {
        $tender = Tender::factory()->create();
        $parent = Criterion::factory()->for($tender)->create();
        $child1 = Criterion::factory()->for($tender)->create(['parent_id' => $parent->id]);
        $child2 = Criterion::factory()->for($tender)->create(['parent_id' => $parent->id]);

        $this->assertCount(2, $parent->children);
        $this->assertTrue($child1->parent->is($parent));
        $this->assertTrue($child2->parent->is($parent));
    }

    public function test_response_belongs_to_participant_and_question(): void
    {
        $tender = Tender::factory()->create();
        $criterion = Criterion::factory()->for($tender)->create();
        $question = Question::factory()->for($criterion)->create();
        $participant = Participant::factory()->for($tender)->create();

        $response = TenderResponse::create([
            'participant_id' => $participant->id,
            'question_id' => $question->id,
            'answer_text' => 'Test answer.',
            'completeness_score' => 0.5,
        ]);

        $this->assertTrue($response->participant->is($participant));
        $this->assertTrue($response->question->is($question));
    }

    public function test_question_has_many_responses(): void
    {
        $tender = Tender::factory()->create();
        $criterion = Criterion::factory()->for($tender)->create();
        $question = Question::factory()->for($criterion)->create();
        $p1 = Participant::factory()->for($tender)->create();
        $p2 = Participant::factory()->for($tender)->create();

        TenderResponse::create(['participant_id' => $p1->id, 'question_id' => $question->id, 'answer_text' => 'A', 'completeness_score' => 0.1]);
        TenderResponse::create(['participant_id' => $p2->id, 'question_id' => $question->id, 'answer_text' => 'B', 'completeness_score' => 0.2]);

        $this->assertCount(2, $question->responses);
    }

    public function test_participant_completion_percent_with_all_answered(): void
    {
        $tender = Tender::factory()->create();
        $criterion = Criterion::factory()->for($tender)->create();
        $q1 = Question::factory()->for($criterion)->create();
        $q2 = Question::factory()->for($criterion)->create();

        $participant = Participant::factory()->for($tender)->active()->create();

        TenderResponse::create(['participant_id' => $participant->id, 'question_id' => $q1->id, 'answer_text' => 'Answer one.', 'completeness_score' => 0.5]);
        TenderResponse::create(['participant_id' => $participant->id, 'question_id' => $q2->id, 'answer_text' => 'Answer two.', 'completeness_score' => 0.5]);

        $this->assertEquals(100, $participant->completion_percent);
    }

    public function test_follow_up_belongs_to_response(): void
    {
        $tender = Tender::factory()->create();
        $criterion = Criterion::factory()->for($tender)->create();
        $question = Question::factory()->for($criterion)->create();
        $participant = Participant::factory()->for($tender)->create();

        $response = TenderResponse::create([
            'participant_id' => $participant->id,
            'question_id' => $question->id,
            'answer_text' => 'Short.',
            'completeness_score' => 0.03,
        ]);

        $followUp = \App\Models\FollowUp::create([
            'response_id' => $response->id,
            'role' => 'ai',
            'message' => 'Could you elaborate?',
            'sequence' => 1,
        ]);

        $this->assertTrue($followUp->response->is($response));
        $this->assertCount(1, $response->followUps);
    }
}
