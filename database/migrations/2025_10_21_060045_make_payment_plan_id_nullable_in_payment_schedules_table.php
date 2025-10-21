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
        Schema::table('payment_schedules', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['payment_plan_id']);
            
            // Make the column nullable
            $table->unsignedBigInteger('payment_plan_id')->nullable()->change();
            
            // Re-add the foreign key constraint (nullable)
            $table->foreign('payment_plan_id')->references('id')->on('payment_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_schedules', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['payment_plan_id']);
            
            // Make the column not nullable again
            $table->unsignedBigInteger('payment_plan_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint (not nullable)
            $table->foreign('payment_plan_id')->references('id')->on('payment_plans')->onDelete('cascade');
        });
    }
};
