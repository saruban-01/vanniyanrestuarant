<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('booking_reference')->unique(); // VAN-E-XXXXX
            
            // Customer Details
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();
            
            // Booking Details
            $table->integer('guest_count');
            $table->string('event_title_snapshot');
            $table->decimal('unit_price_snapshot', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('currency')->default('LKR');
            $table->text('booking_note')->nullable();
            
            // State
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed, no_show, waitlisted
            $table->string('idempotency_key')->unique();
            
            // Terms
            $table->string('terms_version')->nullable();
            $table->timestamp('terms_accepted_at')->nullable();
            
            $table->timestamps();

            // Additional indexes
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_bookings');
    }
};
