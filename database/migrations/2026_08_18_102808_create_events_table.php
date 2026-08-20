<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable(); // e.g., 'Buffet', 'Live Music'
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('hero_image')->nullable();
            
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->default('Vanniyan Restaurant');
            
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bookable')->default(false);
            $table->integer('capacity')->nullable();
            
            $table->dateTime('booking_open_at')->nullable();
            $table->dateTime('booking_close_at')->nullable();
            
            $table->boolean('is_published')->default(false);
            $table->integer('sort_order')->default(0);
            
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance on public list
            $table->index('start_date');
            $table->index('is_published');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
