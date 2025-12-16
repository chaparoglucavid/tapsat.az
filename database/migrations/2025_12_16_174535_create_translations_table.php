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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('group');
            $table->string('key');
            $table->string('locale', 10);
            $table->text('value');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['group', 'key', 'locale']);
            $table->index(['locale', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
