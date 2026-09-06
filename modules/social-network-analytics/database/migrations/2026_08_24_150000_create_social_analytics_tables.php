<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('social_analytics_events', function (Blueprint $t): void {
            $t->id();
            $t->string('name', 120);
            $t->string('subject_type', 120)->nullable();
            $t->uuid('subject_id')->nullable();
            $t->date('occurred_on');
            $t->json('dimensions')->nullable();
            $t->unsignedInteger('value')->default(1);
            $t->timestamps();
            $t->index(['name', 'occurred_on']);
        });
        Schema::create('social_analytics_snapshots', function (Blueprint $t): void {
            $t->id();
            $t->date('period_start');
            $t->date('period_end');
            $t->string('metric', 120);
            $t->unsignedInteger('cohort_size')->default(0);
            $t->decimal('value', 14, 4)->default(0);
            $t->json('dimensions')->nullable();
            $t->timestamps();
            $t->unique(['period_start', 'period_end', 'metric']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_analytics_snapshots');
        Schema::dropIfExists('social_analytics_events');
    }
};
