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
            if (!Schema::hasColumn('cms_homepages', 'site_logo')) {
                $table->string('site_logo')->nullable()->after('id');
            }
            if (!Schema::hasColumn('cms_homepages', 'site_logo_type')) {
                $table->string('site_logo_type')->default('default')->after('site_logo');
            }
            if (!Schema::hasColumn('cms_homepages', 'site_name_highlight')) {
                $table->string('site_name_highlight')->default('AI')->after('site_logo_type');
            }
            if (!Schema::hasColumn('cms_homepages', 'site_name_rest')) {
                $table->string('site_name_rest')->default('Jogja Solutions')->after('site_name_highlight');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_homepages', function (Blueprint $table) {
            $cols = [];
            foreach (['site_logo', 'site_logo_type', 'site_name_highlight', 'site_name_rest'] as $col) {
                if (Schema::hasColumn('cms_homepages', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
