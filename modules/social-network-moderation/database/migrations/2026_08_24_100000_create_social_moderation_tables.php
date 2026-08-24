<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_moderation_reports', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('reporter_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('target_type', 160);
            $table->uuid('target_id');
            $table->string('reason', 120);
            $table->text('details')->nullable();
            $table->string('state', 24)->default('open');
            $table->string('assigned_to', 160)->nullable();
            $table->timestamps();
            $table->index(['state', 'created_at']);
        });
        Schema::create('social_moderation_decisions', function (Blueprint $table): void {
            $table->id();
            $table->uuid('report_id');
            $table->foreignUuid('actor_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('action', 24);
            $table->text('reason')->nullable();
            $table->json('evidence')->nullable();
            $table->timestamps();
            $table->foreign('report_id')->references('id')->on('social_moderation_reports')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_moderation_decisions');
        Schema::dropIfExists('social_moderation_reports');
    }
};
