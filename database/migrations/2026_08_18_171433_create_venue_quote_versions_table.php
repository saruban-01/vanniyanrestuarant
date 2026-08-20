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
        Schema::create('venue_quote_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_booking_id')->constrained('venue_bookings')->onDelete('cascade');
            $table->integer('version_number');
            $table->decimal('venue_fee', 10, 2)->default(0);
            $table->decimal('services_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('quoted_total', 10, 2)->default(0);
            $table->string('currency', 3)->default('LKR');
            $table->foreignId('admin_user_id')->nullable()->constrained('admin_users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venue_quote_versions');
    }
};
