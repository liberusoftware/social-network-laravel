<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_federation_mappings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('remote_type', 80);
            $table->string('remote_id', 500);
            $table->string('local_type', 120);
            $table->uuid('local_id');
            $table->string('state', 30)->default('active')->index();
            $table->json('metadata')->nullable();
            $table->timestamp('last_reconciled_at')->nullable();
            $table->timestamps();
            $table->unique(['remote_type', 'remote_id']);
            $table->index(['local_type', 'local_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_federation_mappings');
    }
};
