<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_message_reactions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('message_id')->constrained('social_messages')->cascadeOnDelete();
            $table->foreignUuid('conversation_id')->constrained('social_conversations')->cascadeOnDelete();
            $table->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('emoji', 32);
            $table->timestamps();
            $table->unique(['message_id', 'profile_id', 'emoji']);
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_message_reactions');
    }
};
