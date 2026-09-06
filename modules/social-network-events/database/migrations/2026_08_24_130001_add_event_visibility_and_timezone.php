<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('social_events', function (Blueprint $table): void {
            $table->string('visibility', 30)->default('public')->index();
            $table->string('timezone', 80)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('social_events', function (Blueprint $table): void {
            $table->dropColumn(['visibility', 'timezone']);
        });
    }
};
