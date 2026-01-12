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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->json('name');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('category_package_prices', function (Blueprint $table) {
            $table->id();
            $table->uuid('package_uuid');
            $table->uuid('category_uuid');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('package_uuid')->references('uuid')->on('packages')->onDelete('cascade');
            $table->foreign('category_uuid')->references('uuid')->on('categories')->onDelete('cascade');
            
            $table->unique(['package_uuid', 'category_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_package_prices');
        Schema::dropIfExists('packages');
    }
};
