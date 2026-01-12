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
        Schema::create('category_attributes', function (Blueprint $table) {
            $table->id();
            $table->uuid('category_uuid');
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(true); // Axtarışda görünsünmü?
            $table->integer('order')->default(0);
            
            $table->foreign('category_uuid')->references('uuid')->on('categories')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_attributes');
    }
};
