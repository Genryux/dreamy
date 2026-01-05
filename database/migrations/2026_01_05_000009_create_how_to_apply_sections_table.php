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
        Schema::create('how_to_apply_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->default('How to Apply');
            $table->text('description')->nullable();
            $table->string('button_text')->default('Apply Now');
            $table->string('button_link')->default('/portal/register');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('how_to_apply_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('how_to_apply_section_id')->constrained()->onDelete('cascade');
            $table->integer('step_number')->default(1);
            $table->string('title');
            $table->text('description');
            $table->string('icon')->nullable(); // Optional icon class
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('how_to_apply_steps');
        Schema::dropIfExists('how_to_apply_sections');
    }
};
