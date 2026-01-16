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
        Schema::table('student_subjects', function (Blueprint $table) {
            $table->enum('remedial_status', ['cleared', 'failed'])->nullable()->after('evaluation_status');
            $table->timestamp('remedial_deadline')->nullable()->after('remedial_status');
            $table->timestamp('finalized_at')->nullable()->after('remedial_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_subjects', function (Blueprint $table) {
            $table->dropColumn(['remedial_status', 'remedial_deadline', 'finalized_at']);
        });
    }
};
