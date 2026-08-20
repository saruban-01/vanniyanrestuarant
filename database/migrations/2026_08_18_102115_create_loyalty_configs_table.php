<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_configs', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->default('COLLECT VISITS. ENJOY REWARDS.');
            $table->text('description')->nullable();
            $table->string('card_image_url')->nullable();
            
            $table->string('visit_5_title')->default('FREE DRINK');
            $table->string('visit_5_reward')->default('Receive a free drink on your 5th eligible visit.');
            
            $table->string('visit_10_title')->default('RS. 1,000 FOOD COUPON');
            $table->string('visit_10_reward')->default('Receive a Rs. 1,000 food coupon on your 10th eligible visit.');
            
            $table->json('how_it_works')->nullable();
            $table->json('terms')->nullable();
            
            $table->string('cta_text')->default('VIEW MENU');
            $table->boolean('is_visible')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_configs');
    }
};
