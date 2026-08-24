<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_feed_controls', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('profile_id')->unique()->constrained('social_profiles')->cascadeOnDelete();
            $table->string('mode', 20)->default('ranked');
            $table->json('filters')->nullable();
            $table->json('hidden_items')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_feed_controls');
    }
};
