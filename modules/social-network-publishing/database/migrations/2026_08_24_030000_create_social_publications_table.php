<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_publications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('author_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('kind', 16)->default('post');
            $table->string('state', 16)->default('draft');
            $table->string('audience', 16)->default('public');
            $table->string('title', 240)->nullable();
            $table->text('body')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['author_profile_id', 'state', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_publications');
    }
};
