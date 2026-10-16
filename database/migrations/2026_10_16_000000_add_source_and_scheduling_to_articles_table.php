<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'source_name')) {
                $table->string('source_name')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('articles', 'source_url')) {
                $table->text('source_url')->nullable()->after('source_name');
            }
            if (!Schema::hasColumn('articles', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });

        // Modifikasi kolom hanya untuk MySQL karena SQLite tidak mendukung ALTER TABLE MODIFY COLUMN
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE articles MODIFY COLUMN gambar TEXT NULL");
            DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft', 'published', 'scheduled') NOT NULL DEFAULT 'published'");
        }

        // Backfill data: artikel published lama yang published_at masih null diisi dengan created_at
        if (Schema::hasColumn('articles', 'status') && Schema::hasColumn('articles', 'published_at')) {
            DB::table('articles')
                ->where('status', 'published')
                ->whereNull('published_at')
                ->update(['published_at' => DB::raw('created_at')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Kembalikan artikel 'scheduled' menjadi 'draft' sebelum rollback ENUM
            DB::table('articles')
                ->where('status', 'scheduled')
                ->update(['status' => 'draft']);

            DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft', 'published') NOT NULL DEFAULT 'published'");
            DB::statement("ALTER TABLE articles MODIFY COLUMN gambar VARCHAR(255) NULL");
        }

        Schema::table('articles', function (Blueprint $table) {
            $cols = [];
            foreach (['source_name', 'source_url', 'published_at'] as $col) {
                if (Schema::hasColumn('articles', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
