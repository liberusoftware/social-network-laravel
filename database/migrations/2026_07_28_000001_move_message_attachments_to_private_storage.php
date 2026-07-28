<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('message_attachments')) {
            return;
        }

        DB::table('message_attachments')
            ->select(['id', 'path'])
            ->orderBy('id')
            ->each(function ($attachment): void {
                if (! Storage::disk('public')->exists($attachment->path)) {
                    return;
                }

                if (! Storage::disk('local')->exists($attachment->path)) {
                    Storage::disk('local')->put(
                        $attachment->path,
                        Storage::disk('public')->get($attachment->path)
                    );
                }

                Storage::disk('public')->delete($attachment->path);
            });
    }

    public function down(): void
    {
        // Intentionally do not move private attachments back onto a public disk.
    }
};
