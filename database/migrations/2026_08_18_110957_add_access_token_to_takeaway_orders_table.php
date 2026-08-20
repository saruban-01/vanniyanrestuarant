<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('takeaway_orders', function (Blueprint $table) {
            $table->string('access_token')->unique()->after('reference')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('takeaway_orders', function (Blueprint $table) {
            $table->dropColumn('access_token');
        });
    }
};
