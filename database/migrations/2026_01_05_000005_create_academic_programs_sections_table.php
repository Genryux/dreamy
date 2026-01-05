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
        Schema::create('academic_programs_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->default('Academic Programs');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('academic_programs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_programs_section_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Program name
            $table->text('description'); // Program description
            $table->string('track_name')->nullable(); // Track name (e.g., "STEM", "ABM")
            $table->string('gradient_from')->default('#1A3165'); // Gradient start color
            $table->string('gradient_to')->default('#2A4A7A'); // Gradient end color
            $table->string('link_url')->nullable(); // Link to program details
            $table->string('status')->default('active'); // active, coming_soon
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_programs_items');
        Schema::dropIfExists('academic_programs_sections');
    }
};
