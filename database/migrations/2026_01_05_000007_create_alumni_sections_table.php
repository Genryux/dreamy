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
        Schema::create('alumni_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('description')->nullable();
            $table->string('background_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('alumni_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_section_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('class_year')->nullable(); // e.g., "Class of 2020"
            $table->string('track')->nullable(); // e.g., "STEM", "ABM"
            $table->text('quote');
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_items');
        Schema::dropIfExists('alumni_sections');
    }
};
