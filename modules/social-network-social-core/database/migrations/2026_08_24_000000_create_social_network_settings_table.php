<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_network_settings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('deployment_mode', 32)->default('hosted');
            $table->json('network_settings');
            $table->json('terminology');
            $table->json('feature_policy');
            $table->json('shared_ids');
            $table->timestamps();
            $table->unique('team_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_network_settings');
    }
};
