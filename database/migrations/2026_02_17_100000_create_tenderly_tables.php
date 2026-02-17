<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tenders - the main workspace
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('objectives')->nullable();
            $table->text('focus_themes')->nullable();
            $table->text('context_notes')->nullable();
            $table->date('deadline')->nullable();
            $table->enum('status', ['draft', 'active', 'reviewing', 'completed', 'archived'])->default('draft');
            $table->timestamps();
        });

        // Documents uploaded per tender
        Schema::create('tender_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->enum('processing_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('extracted_text')->nullable();
            $table->timestamps();
        });

        // Criteria and sub-criteria per tender
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('criteria')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('weight')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // AI-generated questions per criterion
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criterion_id')->constrained('criteria')->cascadeOnDelete();
            $table->text('question_text');
            $table->enum('priority', ['normal', 'high', 'critical'])->default('normal');
            $table->enum('source', ['ai_generated', 'manual', 'seed'])->default('ai_generated');
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('depth_level')->default(1);
            $table->timestamps();
        });

        // Participants invited to a tender
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('role')->nullable();
            $table->string('token', 64)->unique();
            $table->enum('status', ['invited', 'active', 'submitted', 'locked'])->default('invited');
            $table->timestamp('invited_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        // Responses from participants to questions
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->decimal('completeness_score', 3, 2)->default(0);
            $table->timestamps();

            $table->unique(['participant_id', 'question_id']);
        });

        // AI follow-up dialogue per response
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['ai', 'participant']);
            $table->text('message');
            $table->unsignedInteger('sequence')->default(0);
            $table->timestamps();
        });

        // AI analysis results per tender
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['consensus', 'conflicts', 'gaps', 'themes', 'risks', 'insights', 'session_prep']);
            $table->string('title');
            $table->json('content');
            $table->decimal('confidence', 3, 2)->default(0.5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analyses');
        Schema::dropIfExists('follow_ups');
        Schema::dropIfExists('responses');
        Schema::dropIfExists('participants');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('criteria');
        Schema::dropIfExists('tender_documents');
        Schema::dropIfExists('tenders');
    }
};
