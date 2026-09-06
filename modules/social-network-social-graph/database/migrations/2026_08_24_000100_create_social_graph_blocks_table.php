<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_graph_blocks', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('source_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->foreignUuid('target_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['source_profile_id', 'target_profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_graph_blocks');
    }
};
