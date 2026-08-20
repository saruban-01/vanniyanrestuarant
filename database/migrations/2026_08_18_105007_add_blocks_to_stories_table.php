<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->json('blocks')->nullable()->after('content');
            $table->json('sources')->nullable()->after('blocks');
            $table->integer('reading_time_minutes')->nullable()->after('sources');
        });
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn(['blocks', 'sources', 'reading_time_minutes']);
        });
    }
};
