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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('is_active');
            // slug is already unique()
        });

        Schema::table('takeaway_orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('pickup_time');
            $table->index('created_at');
            // reference is already unique()
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->index('table_id');
            // date/time/status already have indexes
        });

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->index('event_id');
            // reference is already unique(), status is already indexed
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('takeaway_orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['pickup_time']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['table_id']);
        });

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropIndex(['event_id']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });
    }
};
