<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_communities', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('slug', 140)->unique();
            $table->text('description')->nullable();
            $table->string('visibility', 16)->default('public');
            $table->json('rules')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('social_community_memberships', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('community_id')->constrained('social_communities')->cascadeOnDelete();
            $table->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('role', 16)->default('member');
            $table->string('status', 16)->default('pending');
            $table->timestamps();
            $table->unique(['community_id', 'profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_community_memberships');
        Schema::dropIfExists('social_communities');
    }
};
