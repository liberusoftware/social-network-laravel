<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_conversations', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('created_by_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('state', 16)->default('active');
            $table->string('title', 200)->nullable();
            $table->timestamps();
        });
        Schema::create('social_conversation_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('conversation_id')->constrained('social_conversations')->cascadeOnDelete();
            $table->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['conversation_id', 'profile_id']);
        });
        Schema::create('social_messages', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('conversation_id')->constrained('social_conversations')->cascadeOnDelete();
            $table->foreignUuid('sender_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->text('body')->nullable();
            $table->string('state', 16)->default('sent');
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_messages');
        Schema::dropIfExists('social_conversation_members');
        Schema::dropIfExists('social_conversations');
    }
};
