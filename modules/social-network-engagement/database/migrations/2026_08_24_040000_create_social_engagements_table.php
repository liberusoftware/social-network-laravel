<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_engagements', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('actor_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('target_type', 160);
            $table->uuid('target_id');
            $table->string('kind', 24);
            $table->string('reaction_type', 24)->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
            $table->index(['target_type', 'target_id', 'kind']);
            $table->unique(['actor_profile_id', 'target_type', 'target_id', 'kind', 'reaction_type'], 'social_engagement_unique_actor_target');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_engagements');
    }
};
