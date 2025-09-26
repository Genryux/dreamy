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
        Schema::create('permission_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->string('category_name');
            $table->text('description')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            
            // Index for better performance
            $table->index('permission_id');
            $table->index('category_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_categories');
    }
};