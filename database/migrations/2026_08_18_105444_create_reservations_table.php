<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_reference')->unique();
            $table->string('customer_name');
            $table->string('phone');
            $table->string('email')->nullable();
            
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->integer('duration_minutes')->default(90);
            $table->integer('guests');
            
            $table->foreignId('table_id')->constrained('restaurant_tables')->restrictOnDelete();
            
            $table->text('special_request')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, completed, no_show
            
            $table->string('idempotency_key')->unique()->nullable();
            
            $table->timestamps();
            
            $table->index(['reservation_date', 'reservation_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
