<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Server-authoritative one-shot flags so conversion events (purchase, booking
     * confirmations) are never pushed twice, even on page refresh.
     */
    public function up(): void
    {
        Schema::table('takeaway_orders', function (Blueprint $table) {
            $table->boolean('purchase_event_sent')->default(false)->after('status');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->boolean('analytics_confirmed_sent')->default(false)->after('status');
        });

        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->boolean('analytics_confirmed_sent')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('takeaway_orders', function (Blueprint $table) {
            $table->dropColumn('purchase_event_sent');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('analytics_confirmed_sent');
        });

        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->dropColumn('analytics_confirmed_sent');
        });
    }
};