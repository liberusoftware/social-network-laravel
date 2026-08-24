<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_media_assets', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('type', 16);
            $table->string('state', 16)->default('pending');
            $table->string('disk', 64);
            $table->string('path', 2048);
            $table->string('mime_type', 160)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('checksum', 128)->nullable();
            $table->string('alt_text', 1000)->nullable();
            $table->text('captions')->nullable();
            $table->json('rights')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['owner_profile_id', 'state', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_media_assets');
    }
};
