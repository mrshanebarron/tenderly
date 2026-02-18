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

    // Content structure validation for all types

    public function test_conflicts_analysis_has_correct_structure(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'conflicts']);

        $analysis = Analysis::where('type', 'conflicts')->first();
        $this->assertArrayHasKey('summary', $analysis->content);
        $this->assertArrayHasKey('conflicts', $analysis->content);
        $this->assertNotEmpty($analysis->content['conflicts']);
        $this->assertArrayHasKey('topic', $analysis->content['conflicts'][0]);
        $this->assertArrayHasKey('positions', $analysis->content['conflicts'][0]);
        $this->assertArrayHasKey('severity', $analysis->content['conflicts'][0]);
    }

    public function test_gaps_analysis_has_correct_structure(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'gaps']);

        $analysis = Analysis::where('type', 'gaps')->first();
        $this->assertArrayHasKey('summary', $analysis->content);
        $this->assertArrayHasKey('gaps', $analysis->content);
        $this->assertArrayHasKey('severity', $analysis->content['gaps'][0]);
    }

    public function test_themes_analysis_has_correct_structure(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'themes']);

        $analysis = Analysis::where('type', 'themes')->first();
        $this->assertArrayHasKey('themes', $analysis->content);
        $this->assertArrayHasKey('theme', $analysis->content['themes'][0]);
        $this->assertArrayHasKey('frequency', $analysis->content['themes'][0]);
        $this->assertArrayHasKey('participants_mentioning', $analysis->content['themes'][0]);
    }

    public function test_risks_analysis_has_correct_structure(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'risks']);

        $analysis = Analysis::where('type', 'risks')->first();
        $this->assertArrayHasKey('risks', $analysis->content);
        $this->assertArrayHasKey('risk', $analysis->content['risks'][0]);
        $this->assertArrayHasKey('probability', $analysis->content['risks'][0]);
        $this->assertArrayHasKey('impact', $analysis->content['risks'][0]);
    }

    public function test_insights_analysis_has_correct_structure(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'insights']);

        $analysis = Analysis::where('type', 'insights')->first();
        $this->assertArrayHasKey('insights', $analysis->content);
        $this->assertArrayHasKey('insight', $analysis->content['insights'][0]);
        $this->assertArrayHasKey('relevance', $analysis->content['insights'][0]);
    }

    public function test_multiple_analyses_can_exist_per_tender(): void
    {
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'consensus']);
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'conflicts']);
        $this->actingAs($this->user)
            ->post(route('analysis.generate', $this->tender), ['type' => 'consensus']);

        $this->assertEquals(3, $this->tender->analyses()->count());
    }
}
