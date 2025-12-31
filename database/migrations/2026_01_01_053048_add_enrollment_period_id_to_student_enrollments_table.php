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
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->foreignId('enrollment_period_id')
                ->nullable()
                ->after('academic_term_id')
                ->constrained('enrollment_periods')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_enrollments', function (Blueprint $table) {
            $table->dropForeign(['enrollment_period_id']);
            $table->dropColumn('enrollment_period_id');
        });
    }
};
