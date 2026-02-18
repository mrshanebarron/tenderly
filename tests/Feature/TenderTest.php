<?php

namespace Tests\Feature;

use App\Models\Criterion;
use App\Models\Participant;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // Index

    public function test_guest_cannot_list_tenders(): void
    {
        $this->get(route('tenders.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_list_own_tenders(): void
    {
        Tender::factory()->for($this->user)->count(3)->create();

        $this->actingAs($this->user)
            ->get(route('tenders.index'))
            ->assertOk()
            ->assertViewIs('tenders.index');
    }

    // Create / Store

    public function test_user_can_view_create_form(): void
    {
        $this->actingAs($this->user)
            ->get(route('tenders.create'))
            ->assertOk()
            ->assertViewIs('tenders.form');
    }

    public function test_user_can_store_tender(): void
    {
        $this->actingAs($this->user)
            ->post(route('tenders.store'), [
                'name' => 'Cloud Migration RFP',
                'description' => 'Migrate to cloud infrastructure.',
                'deadline' => now()->addMonth()->format('Y-m-d'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tenders', [
            'user_id' => $this->user->id,
            'name' => 'Cloud Migration RFP',
            'status' => 'draft',
        ]);
    }

    public function test_store_requires_name(): void
    {
        $this->actingAs($this->user)
            ->post(route('tenders.store'), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_store_rejects_past_deadline(): void
    {
        $this->actingAs($this->user)
            ->post(route('tenders.store'), [
                'name' => 'Test',
                'deadline' => now()->subDay()->format('Y-m-d'),
            ])
            ->assertSessionHasErrors('deadline');
    }

    // Show

    public function test_owner_can_view_tender(): void
    {
        $tender = Tender::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->get(route('tenders.show', $tender))
            ->assertOk()
            ->assertViewIs('tenders.show')
            ->assertViewHas('tender');
    }

    public function test_non_owner_cannot_view_tender(): void
    {
        $other = User::factory()->create();
        $tender = Tender::factory()->for($other)->create();

        $this->actingAs($this->user)
            ->get(route('tenders.show', $tender))
            ->assertForbidden();
    }

    // Edit / Update

    public function test_owner_can_view_edit_form(): void
    {
        $tender = Tender::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->get(route('tenders.edit', $tender))
            ->assertOk()
            ->assertViewIs('tenders.form');
    }

    public function test_owner_can_update_tender(): void
    {
        $tender = Tender::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->put(route('tenders.update', $tender), [
                'name' => 'Updated Name',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tenders', [
            'id' => $tender->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_non_owner_cannot_update_tender(): void
    {
        $other = User::factory()->create();
        $tender = Tender::factory()->for($other)->create();

        $this->actingAs($this->user)
            ->put(route('tenders.update', $tender), ['name' => 'Hacked'])
            ->assertForbidden();
    }

    // Destroy

    public function test_owner_can_delete_tender(): void
    {
        $tender = Tender::factory()->for($this->user)->create();

        $this->actingAs($this->user)
            ->delete(route('tenders.destroy', $tender))
            ->assertRedirect(route('tenders.index'));

        $this->assertDatabaseMissing('tenders', ['id' => $tender->id]);
    }

    public function test_non_owner_cannot_delete_tender(): void
    {
        $other = User::factory()->create();
        $tender = Tender::factory()->for($other)->create();

        $this->actingAs($this->user)
            ->delete(route('tenders.destroy', $tender))
            ->assertForbidden();
    }

    // Status transitions

    public function test_owner_can_activate_tender(): void
    {
        $tender = Tender::factory()->for($this->user)->create(['status' => 'draft']);

        $this->actingAs($this->user)
            ->post(route('tenders.activate', $tender))
            ->assertRedirect();

        $this->assertEquals('active', $tender->fresh()->status);
    }

    public function test_owner_can_move_to_review(): void
    {
        $tender = Tender::factory()->for($this->user)->active()->create();

        $this->actingAs($this->user)
            ->post(route('tenders.review', $tender))
            ->assertRedirect();

        $this->assertEquals('reviewing', $tender->fresh()->status);
    }

    public function test_owner_can_complete_tender(): void
    {
        $tender = Tender::factory()->for($this->user)->reviewing()->create();

        $this->actingAs($this->user)
            ->post(route('tenders.complete', $tender))
            ->assertRedirect();

        $this->assertEquals('completed', $tender->fresh()->status);
    }

    public function test_non_owner_cannot_change_status(): void
    {
        $other = User::factory()->create();
        $tender = Tender::factory()->for($other)->create();

        $this->actingAs($this->user)
            ->post(route('tenders.activate', $tender))
            ->assertForbidden();
    }
}
