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
        Schema::create('venue_booking_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_booking_id')->constrained('venue_bookings')->onDelete('cascade');
            $table->foreignId('venue_service_id')->nullable()->constrained('venue_services')->onDelete('set null');
            $table->string('snapshot_name');
            $table->string('snapshot_price_type');
            $table->decimal('snapshot_base_price', 10, 2);
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->boolean('is_included')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_booking_services');
    }
};
