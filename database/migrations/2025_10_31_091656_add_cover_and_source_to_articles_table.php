<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('content'); // path di storage/app/public/...
            $table->string('source')->nullable()->after('cover_image');  // URL atau teks sumber
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['cover_image', 'source']);
        });
    }
};
