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
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->integer('installment_number'); // 0 for down payment, 1-9 for monthly
            $table->decimal('amount_due', 10, 2); // Expected payment amount
            $table->decimal('amount_paid', 10, 2)->default(0); // Amount actually paid
            $table->date('due_date')->nullable(); // When payment is due
            $table->string('status')->default('pending'); // pending, partial, paid, overdue
            $table->string('description')->nullable(); // e.g., "Down Payment", "Month 1", etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_schedules');
    }
};

