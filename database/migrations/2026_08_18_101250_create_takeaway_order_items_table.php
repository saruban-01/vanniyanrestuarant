<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('takeaway_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('takeaway_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            
            // Snapshots required by rule 94
            $table->string('item_name_snapshot');
            $table->decimal('unit_price_snapshot', 10, 2);
            
            $table->integer('quantity');
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('takeaway_order_items');
    }
};
