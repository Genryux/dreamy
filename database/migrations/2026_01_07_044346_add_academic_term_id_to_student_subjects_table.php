<?php

use App\Models\AcademicTerms;
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
            $table->foreignIdFor(AcademicTerms::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->after('section_subject_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_subjects', function (Blueprint $table) {
            $table->dropForeign(['academic_terms_id']);
            $table->dropColumn('academic_terms_id');
        });
    }
};
