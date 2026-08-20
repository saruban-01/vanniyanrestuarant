<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('takeaway_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // VAN-TA-XXXXX
            $table->string('status')->default('received'); // received, confirmed, preparing, ready, completed, cancelled
            
            // Customer Info
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            
            // Order Info
            $table->text('order_note')->nullable();
            $table->dateTime('pickup_time');
            
            // Financials
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('takeaway_orders');
    }
};
