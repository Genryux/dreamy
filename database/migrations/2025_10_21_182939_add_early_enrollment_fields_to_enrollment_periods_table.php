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
        Schema::table('enrollment_periods', function (Blueprint $table) {
            $table->enum('period_type', ['early', 'regular', 'late'])->default('regular');
            $table->decimal('early_discount_percentage', 5, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_periods', function (Blueprint $table) {
            $table->dropColumn(['period_type', 'early_discount_percentage']);
        });
    }
};
