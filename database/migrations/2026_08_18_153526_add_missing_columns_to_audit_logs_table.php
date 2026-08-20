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
        Schema::table('audit_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('audit_logs', 'module')) {
                $table->string('module')->nullable()->after('action');
            }
            if (!Schema::hasColumn('audit_logs', 'description')) {
                $table->text('description')->nullable()->after('module');
            }
            if (!Schema::hasColumn('audit_logs', 'record_type')) {
                $table->string('record_type')->nullable()->after('description');
            }
            if (!Schema::hasColumn('audit_logs', 'record_id')) {
                $table->unsignedBigInteger('record_id')->nullable()->after('record_type');
            }
            if (!Schema::hasColumn('audit_logs', 'before_data')) {
                $table->json('before_data')->nullable()->after('record_id');
            }
            if (!Schema::hasColumn('audit_logs', 'after_data')) {
                $table->json('after_data')->nullable()->after('before_data');
            }
            if (!Schema::hasColumn('audit_logs', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('after_data');
            }
            if (!Schema::hasColumn('audit_logs', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn([
                'module',
                'description',
                'record_type',
                'record_id',
                'before_data',
                'after_data',
                'ip_address',
                'user_agent',
            ]);
        });
    }
};
