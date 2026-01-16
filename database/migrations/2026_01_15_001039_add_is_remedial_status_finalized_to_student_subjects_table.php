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
            $table->boolean('is_remedial_status_finalized')->default(false)->after('finalized_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_subjects', function (Blueprint $table) {
            $table->dropColumn('is_remedial_status_finalized');
        });
    }
};
