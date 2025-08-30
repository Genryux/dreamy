<?php

use App\Models\SectionSubject;
use App\Models\Student;
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
        // STUDENT_SUBJECTS (pivot: student enrolled in a section_subject)
        Schema::create('student_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(SectionSubject::class)->constrained()->cascadeOnDelete();
            $table->string('status')->default('enrolled'); // enrolled, dropped, withdrawn
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
