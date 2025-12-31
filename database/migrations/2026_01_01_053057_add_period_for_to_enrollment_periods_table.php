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
            // 'new' = for new applicants, 'old' = for continuing/old students
            $table->enum('period_for', ['new', 'old'])
                ->default('new')
                ->after('period_type')
                ->comment('Indicates whether this period is for new applicants or continuing students');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_periods', function (Blueprint $table) {
            $table->dropColumn('period_for');
        });
    }
};
