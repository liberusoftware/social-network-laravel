<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_notification_preferences', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('profile_id')->unique()->constrained('social_profiles')->cascadeOnDelete();
            $table->json('channels')->nullable();
            $table->json('quiet_hours')->nullable();
            $table->json('digest')->nullable();
            $table->timestamps();
        });
        Schema::create('social_notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('kind', 120);
            $table->string('group_key', 160)->nullable();
            $table->string('state', 16)->default('unread');
            $table->string('channel', 16)->default('in_app');
            $table->json('payload')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['profile_id', 'state', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_notifications');
        Schema::dropIfExists('social_notification_preferences');
    }
};
