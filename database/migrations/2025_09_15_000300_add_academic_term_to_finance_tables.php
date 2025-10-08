<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add academic_term_id to invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->after('student_id')->constrained('academic_terms')->nullOnDelete();
            $table->index('academic_term_id');
        });

        // Add academic_term_id to school_fees
        Schema::table('school_fees', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->constrained('academic_terms')->nullOnDelete();
            $table->index('academic_term_id');
        });

        // Add academic_term_id to invoice_items (inherits from invoice but can be independent)
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->constrained('academic_terms')->nullOnDelete();
            $table->index('academic_term_id');
        });

        // Add academic_term_id to invoice_payments
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->foreignId('academic_term_id')->nullable()->constrained('academic_terms')->nullOnDelete();
            $table->index('academic_term_id');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropIndex(['academic_term_id']);
            $table->dropForeign(['academic_term_id']);
            $table->dropColumn('academic_term_id');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropIndex(['academic_term_id']);
            $table->dropForeign(['academic_term_id']);
            $table->dropColumn('academic_term_id');
        });

        Schema::table('school_fees', function (Blueprint $table) {
            $table->dropIndex(['academic_term_id']);
            $table->dropForeign(['academic_term_id']);
            $table->dropColumn('academic_term_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['academic_term_id']);
            $table->dropForeign(['academic_term_id']);
            $table->dropColumn('academic_term_id');
        });
    }
};
