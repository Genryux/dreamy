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
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->decimal('original_amount', 10, 2)->default(0.00)->after('amount');
            $table->decimal('early_discount', 10, 2)->default(0.00);
            $table->decimal('custom_discounts', 10, 2)->default(0.00);
            $table->decimal('total_discount', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn(['original_amount', 'early_discount', 'custom_discounts', 'total_discount']);
        });
    }
};
