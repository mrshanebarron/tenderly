<?php

namespace Tests\Feature;

use App\Models\Criterion;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CriterionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Tender $tender;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->tender = Tender::factory()->for($this->user)->create();
    }

    public function test_owner_can_add_criterion(): void
    {
        $this->actingAs($this->user)
            ->post(route('criteria.store', $this->tender), [
                'name' => 'Technical Capability',
                'weight' => 30,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('criteria', [
            'tender_id' => $this->tender->id,
            'name' => 'Technical Capability',
            'weight' => 30,
        ]);
    }

    public function test_criterion_requires_name(): void
    {
        $this->actingAs($this->user)
            ->post(route('criteria.store', $this->tender), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_criterion_weight_must_be_between_1_and_100(): void
    {
        $this->actingAs($this->user)
            ->post(route('criteria.store', $this->tender), [
                'name' => 'Test',
                'weight' => 150,
            ])
            ->assertSessionHasErrors('weight');
    }

    public function test_criterion_supports_parent_id(): void
    {
        $parent = Criterion::factory()->for($this->tender)->create();

        $this->actingAs($this->user)
            ->post(route('criteria.store', $this->tender), [
                'name' => 'Sub-criterion',
                'parent_id' => $parent->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('criteria', [
            'name' => 'Sub-criterion',
            'parent_id' => $parent->id,
        ]);
    }

    public function test_owner_can_update_criterion(): void
    {
        $criterion = Criterion::factory()->for($this->tender)->create();

        $this->actingAs($this->user)
            ->put(route('criteria.update', $criterion), [
                'name' => 'Updated Name',
                'weight' => 50,
            ])
            ->assertRedirect();

        $this->assertEquals('Updated Name', $criterion->fresh()->name);
    }

    public function test_owner_can_delete_criterion(): void
    {
        $criterion = Criterion::factory()->for($this->tender)->create();

        $this->actingAs($this->user)
            ->delete(route('criteria.destroy', $criterion))
            ->assertRedirect();

        $this->assertDatabaseMissing('criteria', ['id' => $criterion->id]);
    }

    public function test_non_owner_cannot_manage_criteria(): void
    {
        $other = User::factory()->create();
        $otherTender = Tender::factory()->for($other)->create();
        $criterion = Criterion::factory()->for($otherTender)->create();

        $this->actingAs($this->user)
            ->post(route('criteria.store', $otherTender), ['name' => 'Hacked'])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->put(route('criteria.update', $criterion), ['name' => 'Hacked'])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->delete(route('criteria.destroy', $criterion))
            ->assertForbidden();
    }

    public function test_sort_order_auto_increments(): void
    {
        Criterion::factory()->for($this->tender)->create(['sort_order' => 1]);

        $this->actingAs($this->user)
            ->post(route('criteria.store', $this->tender), [
                'name' => 'Second Criterion',
            ])
            ->assertRedirect();

        $second = Criterion::where('name', 'Second Criterion')->first();
        $this->assertEquals(2, $second->sort_order);
    }
}
