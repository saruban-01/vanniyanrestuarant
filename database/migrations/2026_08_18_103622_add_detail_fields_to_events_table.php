<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('capacity');
            $table->string('currency')->default('LKR')->after('price');
            $table->json('gallery')->nullable()->after('hero_image');
            $table->json('featured_menu_items')->nullable()->after('gallery');
            $table->foreignId('featured_offer_id')->nullable()->constrained('offers')->nullOnDelete()->after('featured_menu_items');
            $table->json('faqs')->nullable()->after('description');
            $table->json('terms')->nullable()->after('faqs');
            $table->text('location_address')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['featured_offer_id']);
            $table->dropColumn([
                'price',
                'currency',
                'gallery',
                'featured_menu_items',
                'featured_offer_id',
                'faqs',
                'terms',
                'location_address'
            ]);
        });
    }
};
