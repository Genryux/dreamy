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
        Schema::create('mission_values_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->default('Our Mission & Values');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('mission_values_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mission_values_section_id')->constrained()->onDelete('cascade');
            $table->string('icon')->default('fi fi-rr-bulb'); // Font Awesome or Flaticon class
            $table->string('title');
            $table->text('description');
            $table->string('color')->default('#1A3165'); // Hex color for icon and title
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_values_items');
        Schema::dropIfExists('mission_values_sections');
    }
};
