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
        Schema::create('campus_tour_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('campus_tour_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_tour_section_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->string('icon')->default('fi-rr-marker'); // Flaticon class
            $table->string('highlight')->nullable(); // e.g., "Located at the heart of campus"
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campus_tour_items');
        Schema::dropIfExists('campus_tour_sections');
    }
};
