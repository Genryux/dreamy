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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Scholarship", "Financial Aid", "Sibling Discount"
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed']); // percentage or fixed amount
            $table->decimal('discount_value', 10, 2); // percentage (0-100) or fixed amount
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
