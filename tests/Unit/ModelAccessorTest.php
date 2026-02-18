<?php

namespace Tests\Unit;

use App\Models\Analysis;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Tender;
use App\Models\TenderDocument;
use PHPUnit\Framework\TestCase;

class ModelAccessorTest extends TestCase
{
    // Tender status_color

    public function test_tender_draft_status_color(): void
    {
        $tender = new Tender(['status' => 'draft']);
        $this->assertStringContainsString('zinc', $tender->status_color);
    }

    public function test_tender_active_status_color(): void
    {
        $tender = new Tender(['status' => 'active']);
        $this->assertStringContainsString('blue', $tender->status_color);
    }

    public function test_tender_reviewing_status_color(): void
    {
        $tender = new Tender(['status' => 'reviewing']);
        $this->assertStringContainsString('amber', $tender->status_color);
    }

    public function test_tender_completed_status_color(): void
    {
        $tender = new Tender(['status' => 'completed']);
        $this->assertStringContainsString('emerald', $tender->status_color);
    }

    // Participant status_color

    public function test_participant_invited_status_color(): void
    {
        $p = new Participant(['status' => 'invited']);
        $this->assertStringContainsString('zinc', $p->status_color);
    }

    public function test_participant_active_status_color(): void
    {
        $p = new Participant(['status' => 'active']);
        $this->assertStringContainsString('blue', $p->status_color);
    }

    public function test_participant_submitted_status_color(): void
    {
        $p = new Participant(['status' => 'submitted']);
        $this->assertStringContainsString('emerald', $p->status_color);
    }

    public function test_participant_locked_status_color(): void
    {
        $p = new Participant(['status' => 'locked']);
        $this->assertStringContainsString('red', $p->status_color);
    }

    // Question priority_color

    public function test_question_critical_priority_color(): void
    {
        $q = new Question(['priority' => 'critical']);
        $this->assertStringContainsString('red', $q->priority_color);
    }

    public function test_question_high_priority_color(): void
    {
        $q = new Question(['priority' => 'high']);
        $this->assertStringContainsString('amber', $q->priority_color);
    }

    public function test_question_normal_priority_color(): void
    {
        $q = new Question(['priority' => 'normal']);
        $this->assertStringContainsString('zinc', $q->priority_color);
    }

    // Analysis type_color and type_icon

    public function test_analysis_type_color_consensus(): void
    {
        $a = new Analysis(['type' => 'consensus']);
        $this->assertStringContainsString('emerald', $a->type_color);
    }

    public function test_analysis_type_color_conflicts(): void
    {
        $a = new Analysis(['type' => 'conflicts']);
        $this->assertStringContainsString('red', $a->type_color);
    }

    public function test_analysis_type_icon_returns_svg_path(): void
    {
        $a = new Analysis(['type' => 'consensus']);
        $this->assertStringStartsWith('M', $a->type_icon);
    }

    // TenderDocument human_size

    public function test_document_human_size_bytes(): void
    {
        $d = new TenderDocument(['size' => 500]);
        $this->assertEquals('500 B', $d->human_size);
    }

    public function test_document_human_size_kilobytes(): void
    {
        $d = new TenderDocument(['size' => 2048]);
        $this->assertEquals('2 KB', $d->human_size);
    }

    public function test_document_human_size_megabytes(): void
    {
        $d = new TenderDocument(['size' => 3145728]);
        $this->assertEquals('3 MB', $d->human_size);
    }

    // Participant token generation

    public function test_generate_token_returns_48_chars(): void
    {
        $token = Participant::generateToken();
        $this->assertEquals(48, strlen($token));
    }

    public function test_generate_token_is_unique(): void
    {
        $tokens = array_map(fn() => Participant::generateToken(), range(1, 10));
        $this->assertCount(10, array_unique($tokens));
    }
}
