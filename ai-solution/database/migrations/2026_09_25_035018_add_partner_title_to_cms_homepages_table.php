<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cms_homepages', function (Blueprint $table) {
            $table->string('partner_title')->default('Mitra & Kolaborasi')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_homepages', function (Blueprint $table) {
            $table->dropColumn('partner_title');
        });
    }
};
