<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('collection_points', function (Blueprint $table) {
            // tambah kolom link peta (boleh null untuk data lama)
            $table->string('map_url', 500)->nullable()->after('contact');
        });
    }

    public function down(): void
    {
        Schema::table('collection_points', function (Blueprint $table) {
            $table->dropColumn('map_url');
        });
    }
};
