<?php

use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\EnrollmentPeriod;
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
        Schema::create('application_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AcademicTerms::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(EnrollmentPeriod::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Applicant::class)->constrained()->cascadeOnDelete();
            $table->bigInteger('lrn');
            $table->string('full_name');
            $table->integer('age');
            $table->date('birthdate');
            $table->string('desired_program');
            $table->string('grade_level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_forms');
    }
};
