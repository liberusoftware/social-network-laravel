<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_feed_entries', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('viewer_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('item_type', 160);
            $table->uuid('item_id');
            $table->decimal('rank', 16, 8)->default(0);
            $table->timestamp('visible_at')->nullable();
            $table->timestamps();
            $table->unique(['viewer_profile_id', 'item_type', 'item_id']);
            $table->index(['viewer_profile_id', 'rank', 'visible_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_feed_entries');
    }
};
