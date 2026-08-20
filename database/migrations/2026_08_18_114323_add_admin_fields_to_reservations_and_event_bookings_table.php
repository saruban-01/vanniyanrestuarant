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
        Schema::table('reservations', function (Blueprint $table) {
            $table->text('admin_note')->nullable()->after('special_request');
            $table->string('cancellation_reason')->nullable()->after('status');
        });

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->text('admin_note')->nullable()->after('booking_note');
            $table->string('cancellation_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['admin_note', 'cancellation_reason']);
        });

        Schema::table('event_bookings', function (Blueprint $table) {
            $table->dropColumn(['admin_note', 'cancellation_reason']);
        });
    }
};
