<?php

namespace Tests\Feature;

use App\Models\Tender;
use App\Models\TenderDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
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

    public function test_owner_can_upload_pdf(): void
    {
        Storage::fake('local');

        $this->actingAs($this->user)
            ->post(route('documents.store', $this->tender), [
                'document' => UploadedFile::fake()->create('tender-spec.pdf', 1024, 'application/pdf'),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tender_documents', [
            'tender_id' => $this->tender->id,
            'original_name' => 'tender-spec.pdf',
            'processing_status' => 'completed',
        ]);
    }

    public function test_upload_requires_file(): void
    {
        $this->actingAs($this->user)
            ->post(route('documents.store', $this->tender), [])
            ->assertSessionHasErrors('document');
    }

    public function test_upload_rejects_invalid_mime_type(): void
    {
        Storage::fake('local');

        $this->actingAs($this->user)
            ->post(route('documents.store', $this->tender), [
                'document' => UploadedFile::fake()->create('malware.exe', 500, 'application/x-msdownload'),
            ])
            ->assertSessionHasErrors('document');
    }

    public function test_upload_rejects_oversized_file(): void
    {
        Storage::fake('local');

        $this->actingAs($this->user)
            ->post(route('documents.store', $this->tender), [
                'document' => UploadedFile::fake()->create('huge.pdf', 20480, 'application/pdf'),
            ])
            ->assertSessionHasErrors('document');
    }

    public function test_owner_can_delete_document(): void
    {
        Storage::fake('local');

        $document = TenderDocument::factory()->for($this->tender)->create([
            'filename' => 'tender-documents/1/test.pdf',
        ]);

        $this->actingAs($this->user)
            ->delete(route('documents.destroy', $document))
            ->assertRedirect();

        $this->assertDatabaseMissing('tender_documents', ['id' => $document->id]);
    }

    public function test_non_owner_cannot_manage_documents(): void
    {
        Storage::fake('local');

        $other = User::factory()->create();
        $otherTender = Tender::factory()->for($other)->create();
        $document = TenderDocument::factory()->for($otherTender)->create();

        $this->actingAs($this->user)
            ->post(route('documents.store', $otherTender), [
                'document' => UploadedFile::fake()->create('hack.pdf', 100, 'application/pdf'),
            ])
            ->assertForbidden();

        $this->actingAs($this->user)
            ->delete(route('documents.destroy', $document))
            ->assertForbidden();
    }
}
