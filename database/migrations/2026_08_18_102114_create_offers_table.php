<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            
            $table->string('type')->default('discount'); // e.g., discount, free_item, bundle
            $table->string('price_or_discount')->nullable(); // e.g., "20% OFF", "Rs. 500"
            
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_until')->nullable();
            
            $table->boolean('is_dine_in')->default(true);
            $table->boolean('is_takeaway')->default(true);
            
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            
            $table->text('terms')->nullable();
            
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
