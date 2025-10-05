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
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2); // Total invoice amount
            $table->decimal('down_payment_amount', 10, 2); // Down payment amount
            $table->decimal('remaining_amount', 10, 2); // Amount remaining after down payment
            $table->integer('installment_months')->default(9); // Number of monthly installments
            $table->decimal('monthly_amount', 10, 2); // Regular monthly payment amount
            $table->decimal('first_month_amount', 10, 2); // First month amount (may include down payment shortfall)
            $table->string('payment_type')->default('installment'); // 'installment' or 'full'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
    }
};
