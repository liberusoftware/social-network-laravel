<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('social_messages', function (Blueprint $table): void {
            $table->text('body')->nullable()->change();
            $table->text('encrypted_body')->nullable()->after('body');
            $table->string('encryption_key_id', 128)->nullable()->after('encrypted_body');
        });
    }

    public function down(): void
    {
        Schema::table('social_messages', function (Blueprint $table): void {
            $table->dropColumn(['encrypted_body', 'encryption_key_id']);
            $table->text('body')->nullable(false)->change();
        });
    }
};
