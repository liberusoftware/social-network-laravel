<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_publication_edits', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('publication_id')->constrained('social_publications')->cascadeOnDelete();
            $table->foreignUuid('editor_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->json('snapshot');
            $table->timestamps();
            $table->index(['publication_id', 'created_at']);
        });
        Schema::create('social_publication_mentions', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('publication_id')->constrained('social_publications')->cascadeOnDelete();
            $table->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['publication_id', 'profile_id']);
        });
        Schema::create('social_publication_hashtags', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('publication_id')->constrained('social_publications')->cascadeOnDelete();
            $table->string('tag', 80);
            $table->timestamps();
            $table->unique(['publication_id', 'tag']);
            $table->index('tag');
        });
        Schema::create('social_publication_polls', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('publication_id')->unique()->constrained('social_publications')->cascadeOnDelete();
            $table->json('options');
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
        });
        Schema::create('social_publication_links', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('publication_id')->constrained('social_publications')->cascadeOnDelete();
            $table->string('url', 2048);
            $table->string('title', 240)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index('url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_publication_links');
        Schema::dropIfExists('social_publication_polls');
        Schema::dropIfExists('social_publication_hashtags');
        Schema::dropIfExists('social_publication_mentions');
        Schema::dropIfExists('social_publication_edits');
    }
};
