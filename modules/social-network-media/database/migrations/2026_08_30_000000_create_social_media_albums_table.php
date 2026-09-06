<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_media_albums', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_profile_id')->constrained('social_profiles')->cascadeOnDelete();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('privacy', 16)->default('private');
            $table->string('cover_path', 2048)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['owner_profile_id', 'privacy']);
        });

        Schema::table('social_media_assets', function (Blueprint $table): void {
            $table->foreignUuid('album_id')->nullable()->after('owner_profile_id')->constrained('social_media_albums')->nullOnDelete();
            $table->index(['album_id', 'state']);
        });
    }

    public function down(): void
    {
        Schema::table('social_media_assets', function (Blueprint $table): void {
            $table->dropForeign(['album_id']);
            $table->dropIndex(['album_id', 'state']);
            $table->dropColumn('album_id');
        });

        Schema::dropIfExists('social_media_albums');
    }
};
