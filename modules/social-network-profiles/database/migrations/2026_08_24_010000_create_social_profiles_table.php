<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_profiles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('handle', 32)->unique();
            $table->text('bio')->nullable();
            $table->json('attributes')->nullable();
            $table->string('avatar_path', 2048)->nullable();
            $table->string('verification_status', 16)->default('unverified');
            $table->string('visibility', 16)->default('public');
            $table->string('lifecycle_state', 16)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('social_profile_blocks', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('blocker_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->foreignUuid('blocked_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['blocker_profile_id', 'blocked_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_profile_blocks');
        Schema::dropIfExists('social_profiles');
    }
};
