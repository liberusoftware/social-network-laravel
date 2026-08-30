<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_discovery_index', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('resource_type', 120);
            $table->uuid('resource_id');
            $table->uuid('owner_profile_id')->nullable();
            $table->string('visibility', 16)->default('public');
            $table->text('body');
            $table->json('terms')->nullable();
            $table->unsignedInteger('engagement_score')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['resource_type', 'resource_id']);
            $table->index(['visibility', 'published_at']);
            $table->index(['resource_type', 'engagement_score']);
        });
    }

    public function down(): void { Schema::dropIfExists('social_discovery_index'); }
};
