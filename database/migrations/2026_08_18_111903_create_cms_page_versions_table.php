<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_page_id')->constrained()->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('status'); // DRAFT, PUBLISHED, ARCHIVED
            $table->json('content')->nullable(); // Page specific content structure
            $table->json('seo_meta')->nullable(); // SEO metadata
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->timestamps();

            // Only one published or draft version per page at a time.
            // Archived versions can be multiple. We'll manage this logic in the service.
            $table->index(['cms_page_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_page_versions');
    }
};
