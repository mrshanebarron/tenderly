<?php

namespace Tests\Feature;

use App\Models\Analysis;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalysisTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Tender $tender;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->tender = Tender::factory()->for($this->user)->active()->create();
    }

    public function test_owner_can_view_analysis_page(): void
    {
        $this->actingAs($this->user)
            ->get(route('analysis.show', $this->tender))
            ->assertOk()
            ->assertViewIs('tenders.analysis');
    }

    public function test_non_owner_cannot_view_analysis(): void
    {
        $other = User::factory()->create();

        $this->actingAs($other)
            ->get(route('analysis.show', $this->tender))
            ->assertForbidden();
    }

    public function test_owner_can_generate_consensus_analysis(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'consensus'])
            ->assertRedirect();

        $analysis = Analysis::first();
        $this->assertEquals('consensus', $analysis->type);
        $this->assertEquals('Consensus Points Analysis', $analysis->title);
        $this->assertIsArray($analysis->content);
        $this->assertArrayHasKey('summary', $analysis->content);
        $this->assertGreaterThanOrEqual(0.65, (float) $analysis->confidence);
        $this->assertLessThanOrEqual(0.95, (float) $analysis->confidence);
    }

    public function test_owner_can_generate_conflicts_analysis(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'conflicts'])
            ->assertRedirect();

        $this->assertDatabaseHas('analyses', [
            'tender_id' => $this->tender->id,
            'type' => 'conflicts',
            'title' => 'Conflicting Viewpoints Report',
        ]);
    }

    public function test_owner_can_generate_gaps_analysis(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'gaps'])
            ->assertRedirect();

        $this->assertDatabaseHas('analyses', [
            'type' => 'gaps',
            'title' => 'Missing & Weak Areas Assessment',
        ]);
    }

    public function test_owner_can_generate_themes_analysis(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'themes'])
            ->assertRedirect();

        $this->assertDatabaseHas('analyses', ['type' => 'themes']);
    }

    public function test_owner_can_generate_risks_analysis(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'risks'])
            ->assertRedirect();

        $this->assertDatabaseHas('analyses', ['type' => 'risks']);
    }

    public function test_owner_can_generate_insights_analysis(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'insights'])
            ->assertRedirect();

        $this->assertDatabaseHas('analyses', ['type' => 'insights']);
    }

    public function test_owner_can_generate_session_prep_analysis(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'session_prep'])
            ->assertRedirect();

        $analysis = Analysis::where('type', 'session_prep')->first();
        $this->assertArrayHasKey('discussion_topics', $analysis->content);
        $this->assertArrayHasKey('clarification_questions', $analysis->content);
        $this->assertArrayHasKey('decision_points', $analysis->content);
    }

    public function test_owner_can_view_session_prep_page(): void
    {
        $this->actingAs($this->user)
            ->get(route('analysis.session-prep', $this->tender))
            ->assertOk()
            ->assertViewIs('tenders.session-prep');
    }

    public function test_non_owner_cannot_generate_or_view_session_prep(): void
    {
        $other = User::factory()->create();

        $this->actingAs($other)
            ->post(route('analysis.generate', $this->tender), ['type' => 'consensus'])
            ->assertForbidden();

        $this->actingAs($other)
            ->get(route('analysis.session-prep', $this->tender))
            ->assertForbidden();
    }
}
