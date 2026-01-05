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
        Schema::create('school_at_glance_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->default('School at a Glance');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('school_at_glance_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_at_glance_section_id')->constrained()->onDelete('cascade');
            $table->string('value'); // e.g., "500+", "95%"
            $table->string('label'); // e.g., "Active Students", "Graduation Rate"
            $table->string('bg_color')->default('#1A3165'); // Background color
            $table->string('text_color')->default('#FFFFFF'); // Text color
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_at_glance_items');
        Schema::dropIfExists('school_at_glance_sections');
    }
};
