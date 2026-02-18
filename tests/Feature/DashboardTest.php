<?php

namespace Tests\Feature;

use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard')
            ->assertViewHas('stats')
            ->assertViewHas('recentTenders');
    }

    public function test_dashboard_shows_correct_stats(): void
    {
        $user = User::factory()->create();

        Tender::factory()->for($user)->create(['status' => 'draft']);
        Tender::factory()->for($user)->active()->create();
        Tender::factory()->for($user)->active()->create();
        Tender::factory()->for($user)->reviewing()->create();
        Tender::factory()->for($user)->completed()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('stats', [
                'total' => 5,
                'active' => 2,
                'reviewing' => 1,
                'completed' => 1,
            ]);
    }

    public function test_dashboard_only_shows_own_tenders(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Tender::factory()->for($user)->create(['name' => 'My Tender']);
        Tender::factory()->for($other)->create(['name' => 'Not Mine']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $recentTenders = $response->viewData('recentTenders');
        $this->assertCount(1, $recentTenders);
        $this->assertEquals('My Tender', $recentTenders->first()->name);
    }

    public function test_dashboard_limits_recent_tenders_to_six(): void
    {
        $user = User::factory()->create();

        Tender::factory()->for($user)->count(10)->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $this->assertCount(6, $response->viewData('recentTenders'));
    }
}
