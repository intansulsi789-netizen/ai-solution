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
            $table->string('source_name')->nullable()->after('kategori');
            $table->text('source_url')->nullable()->after('source_name');
            $table->timestamp('published_at')->nullable()->after('status');
        });

        // Ubah kolom gambar menjadi TEXT agar dapat menampung URL eksternal yang panjang
        DB::statement("ALTER TABLE articles MODIFY COLUMN gambar TEXT NULL");

        // Perluas status ENUM agar mendukung 'scheduled'
        DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft', 'published', 'scheduled') NOT NULL DEFAULT 'published'");

        // Backfill data: artikel published lama yang published_at masih null diisi dengan created_at
        DB::table('articles')
            ->where('status', 'published')
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan artikel 'scheduled' menjadi 'draft' sebelum rollback ENUM
        DB::table('articles')
            ->where('status', 'scheduled')
            ->update(['status' => 'draft']);

        DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft', 'published') NOT NULL DEFAULT 'published'");
        DB::statement("ALTER TABLE articles MODIFY COLUMN gambar VARCHAR(255) NULL");

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['source_name', 'source_url', 'published_at']);
        });
    }
};
