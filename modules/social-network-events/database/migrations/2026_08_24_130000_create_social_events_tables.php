<?php

declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_events', function (Blueprint $t): void {
            $t->uuid('id')->primary();
            $t->foreignUuid('owner_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $t->string('state', 16)->default('draft');
            $t->string('title', 200);
            $t->text('description')->nullable();
            $t->timestamp('starts_at');
            $t->timestamp('ends_at')->nullable();
            $t->unsignedInteger('capacity')->nullable();
            $t->json('location')->nullable();
            $t->json('metadata')->nullable();
            $t->timestamps();
            $t->softDeletes();
            $t->index(['state', 'starts_at']);
        });
        Schema::create('social_event_invitations', function (Blueprint $t): void {
            $t->id();
            $t->foreignUuid('event_id')->constrained('social_events')->cascadeOnDelete();
            $t->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $t->string('state', 16)->default('pending');
            $t->timestamps();
            $t->unique(['event_id', 'profile_id']);
        });
        Schema::create('social_event_attendance', function (Blueprint $t): void {
            $t->id();
            $t->foreignUuid('event_id')->constrained('social_events')->cascadeOnDelete();
            $t->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $t->string('state', 16)->default('going');
            $t->timestamps();
            $t->unique(['event_id', 'profile_id']);
        });
        Schema::create('social_event_updates', function (Blueprint $t): void {
            $t->id();
            $t->foreignUuid('event_id')->constrained('social_events')->cascadeOnDelete();
            $t->foreignUuid('author_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $t->text('body');
            $t->timestamps();
        });
        Schema::create('social_event_reminders', function (Blueprint $t): void {
            $t->id();
            $t->foreignUuid('event_id')->constrained('social_events')->cascadeOnDelete();
            $t->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $t->timestamp('send_at');
            $t->timestamp('sent_at')->nullable();
            $t->timestamps();
            $t->unique(['event_id', 'profile_id', 'send_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_event_reminders');
        Schema::dropIfExists('social_event_updates');
        Schema::dropIfExists('social_event_attendance');
        Schema::dropIfExists('social_event_invitations');
        Schema::dropIfExists('social_events');
    }
};
