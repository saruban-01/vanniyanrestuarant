<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('menu_item_modifier_group');
        Schema::dropIfExists('modifier_options');
        Schema::dropIfExists('modifier_groups');
        Schema::dropIfExists('takeaway_order_item_modifiers');

        // Remove stale index referencing a non-existent column, which blocks the column drop on SQLite
        DB::statement('DROP INDEX IF EXISTS menu_items_category_id_index');

        if (Schema::hasColumn('menu_items', 'labels')) {
            Schema::table('menu_items', function (Blueprint $table) {
                $table->dropColumn('labels');
            });
        }
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->json('labels')->nullable();
        });
    }
};