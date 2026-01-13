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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('announcement_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->integer('duration_days')->default(30)->after('is_active');
        });
        
        // Check if expires_at already exists in announcements, if not add it
        if (!Schema::hasColumn('announcements', 'expires_at')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->timestamp('expires_at')->nullable()->after('published_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['announcement_id']);
            $table->dropForeign(['package_id']);
            $table->dropColumn(['announcement_id', 'package_id']);
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('duration_days');
        });

        if (Schema::hasColumn('announcements', 'expires_at')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->dropColumn('expires_at');
            });
        }
    }
};
