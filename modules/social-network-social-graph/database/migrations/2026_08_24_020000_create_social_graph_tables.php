<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_graph_relationships', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('source_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->foreignUuid('target_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('relationship_type', 16);
            $table->string('status', 16)->default('accepted');
            $table->string('visibility', 16)->default('followers');
            $table->timestamps();
            $table->unique(['source_profile_id', 'target_profile_id', 'relationship_type']);
        });
        Schema::create('social_graph_lists', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('name', 80);
            $table->string('visibility', 16)->default('private');
            $table->timestamps();
            $table->unique(['owner_profile_id', 'name']);
        });
        Schema::create('social_graph_list_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignUuid('list_id')->constrained('social_graph_lists')->cascadeOnDelete();
            $table->foreignUuid('profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['list_id', 'profile_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_graph_list_members');
        Schema::dropIfExists('social_graph_lists');
        Schema::dropIfExists('social_graph_relationships');
    }
};
