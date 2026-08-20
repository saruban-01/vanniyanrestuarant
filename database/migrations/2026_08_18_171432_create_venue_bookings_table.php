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
        Schema::create('venue_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('secure_token')->unique();
            $table->foreignId('venue_id')->constrained('venues')->onDelete('restrict');
            $table->foreignId('event_type_id')->nullable()->constrained('venue_event_types')->onDelete('set null');
            $table->string('event_title');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->integer('guest_count');
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('special_request')->nullable();
            $table->string('status')->default('requested'); // requested, under_review, quote_pending, quote_sent, approved, declined, cancelled, completed
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_bookings');
    }
};
