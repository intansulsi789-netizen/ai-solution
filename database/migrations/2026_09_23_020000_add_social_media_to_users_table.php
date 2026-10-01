<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sosmed_instagram')->nullable()->after('foto');
            $table->string('sosmed_linkedin')->nullable()->after('sosmed_instagram');
            $table->string('sosmed_youtube')->nullable()->after('sosmed_linkedin');
            $table->string('sosmed_tiktok')->nullable()->after('sosmed_youtube');
            $table->string('sosmed_whatsapp')->nullable()->after('sosmed_tiktok');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sosmed_instagram',
                'sosmed_linkedin',
                'sosmed_youtube',
                'sosmed_tiktok',
                'sosmed_whatsapp',
            ]);
        });
    }
};
