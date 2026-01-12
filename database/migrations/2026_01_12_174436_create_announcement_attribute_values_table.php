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
        Schema::create('announcement_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('announcement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            
            // Dəyər mətn kimi saxlanılır, lazım olduqda cast edilir.
            // Select tipli atributlar üçün attribute_option_id saxlanıla bilər, amma sadəlik üçün value saxlayırıq
            // və ya ayrıca option_id sütunu da əlavə edə bilərik. Daha optimal variant option_id-dir.
            
            $table->text('value')->nullable(); // Text, Number inputs
            $table->foreignId('attribute_option_id')->nullable()->constrained()->nullOnDelete(); // Select inputs
            
            $table->timestamps();
            
            // Eyni elan eyni atributa yalnız bir dəfə qiymət verə bilər (çoxseçimli istisna olmaqla)
            // Çoxseçimli (multiselect) olacaqsa unique index götürülməlidir. Hələlik sadə saxlayırıq.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_attribute_values');
    }
};
