<?php

namespace Tests\Feature;

use App\Models\Participant;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantTest extends TestCase
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

    public function test_owner_can_invite_participant(): void
    {
        $this->actingAs($this->user)
            ->post(route('participants.store', $this->tender), [
                'name' => 'Jan de Vries',
                'email' => 'jan@example.com',
                'role' => 'Solution Architect',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('participants', [
            'tender_id' => $this->tender->id,
            'name' => 'Jan de Vries',
            'email' => 'jan@example.com',
        ]);

        $participant = Participant::where('email', 'jan@example.com')->first();
        $this->assertNotNull($participant->token);
        $this->assertNotNull($participant->invited_at);
    }

    public function test_participant_requires_name_and_email(): void
    {
        $this->actingAs($this->user)
            ->post(route('participants.store', $this->tender), [])
            ->assertSessionHasErrors(['name', 'email']);
    }

    public function test_participant_email_must_be_valid(): void
    {
        $this->actingAs($this->user)
            ->post(route('participants.store', $this->tender), [
                'name' => 'Test',
                'email' => 'not-an-email',
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_owner_can_resend_invitation(): void
    {
        $participant = Participant::factory()->for($this->tender)->create([
            'invited_at' => now()->subDays(5),
        ]);

        $oldInvited = $participant->invited_at;

        $this->travel(1)->seconds();

        $this->actingAs($this->user)
            ->post(route('participants.resend', $participant))
            ->assertRedirect();

        $this->assertTrue($participant->fresh()->invited_at->gt($oldInvited));
    }

    public function test_owner_can_lock_participant(): void
    {
        $participant = Participant::factory()->for($this->tender)->active()->create();

        $this->actingAs($this->user)
            ->post(route('participants.lock', $participant))
            ->assertRedirect();

        $this->assertEquals('locked', $participant->fresh()->status);
    }

    public function test_owner_can_delete_participant(): void
    {
        $participant = Participant::factory()->for($this->tender)->create();

        $this->actingAs($this->user)
            ->delete(route('participants.destroy', $participant))
            ->assertRedirect();

        $this->assertDatabaseMissing('participants', ['id' => $participant->id]);
    }

    public function test_non_owner_cannot_manage_participants(): void
    {
        $other = User::factory()->create();
        $otherTender = Tender::factory()->for($other)->create();
        $participant = Participant::factory()->for($otherTender)->create();

        $this->actingAs($this->user)
            ->post(route('participants.store', $otherTender), [
                'name' => 'Hacker',
                'email' => 'hack@example.com',
            ])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->post(route('participants.resend', $participant))
            ->assertForbidden();

        $this->actingAs($this->user)
            ->post(route('participants.lock', $participant))
            ->assertForbidden();

        $this->actingAs($this->user)
            ->delete(route('participants.destroy', $participant))
            ->assertForbidden();
    }

    public function test_token_is_unique_48_chars(): void
    {
        $token = Participant::generateToken();
        $this->assertEquals(48, strlen($token));

        $token2 = Participant::generateToken();
        $this->assertNotEquals($token, $token2);
    }
}
