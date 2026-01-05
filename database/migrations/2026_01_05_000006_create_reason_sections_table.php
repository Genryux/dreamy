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
        Schema::create('reason_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('reason_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reason_section_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reason_items');
        Schema::dropIfExists('reason_sections');
    }
};
