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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Relations
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // Validation levelinde yoxlanacaq ki, alt kategoriya olsun
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            
            // Common Info
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('AZN'); // AZN, USD
            
            // Flags
            $table->boolean('is_new')->default(false); // Yeni/İşlənmiş
            $table->boolean('has_delivery')->default(false); // Çatdırılma
            
            // Status & Meta
            $table->enum('status', ['pending', 'active', 'rejected', 'expired', 'sold'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->integer('view_count')->default(0);
            
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for faster filtering
            $table->index(['category_id', 'status']);
            $table->index(['city_id', 'status']);
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
