<?php

use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\Applicants;
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
            $table->foreignIdFor(Applicants::class)->constrained()->cascadeOnDelete();

            // Enrollment Info
            $table->string('preferred_sched')->nullable();
            $table->boolean('is_returning')->nullable();
            $table->string('lrn')->nullable();
            $table->string('grade_level')->nullable();
            $table->string('primary_track')->nullable();
            $table->string('secondary_track')->nullable();
            $table->string('acad_term_applied')->nullable();
            $table->string('semester_applied')->nullable();
            $table->dateTime('admission_date')->nullable();

            // Personal Info
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('gender')->nullable(); //
            $table->date('birthdate')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->boolean('belongs_to_ip')->nullable();
            $table->boolean('is_4ps_beneficiary')->nullable();
            $table->string('contact_number')->nullable(); //

            // Current Address
            $table->string('cur_house_no')->nullable();
            $table->string('cur_street')->nullable();
            $table->string('cur_barangay')->nullable();
            $table->string('cur_city')->nullable();
            $table->string('cur_province')->nullable();
            $table->string('cur_country')->nullable();
            $table->string('cur_zip_code')->nullable();

            // Permanent Address
            $table->string('perm_house_no')->nullable();
            $table->string('perm_street')->nullable();
            $table->string('perm_barangay')->nullable();
            $table->string('perm_city')->nullable();
            $table->string('perm_province')->nullable();
            $table->string('perm_country')->nullable();
            $table->string('perm_zip_code')->nullable();

            // Parent Info
            $table->string('father_last_name')->nullable();
            $table->string('father_first_name')->nullable();
            $table->string('father_middle_name')->nullable();
            $table->string('father_contact_number')->nullable();

            $table->string('mother_last_name')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_middle_name')->nullable();
            $table->string('mother_contact_number')->nullable();

            $table->string('guardian_last_name')->nullable();
            $table->string('guardian_first_name')->nullable();
            $table->string('guardian_middle_name')->nullable();
            $table->string('guardian_contact_number')->nullable();

            // Special Needs
            $table->boolean('has_special_needs')->nullable();
            $table->string('special_needs')->nullable(); // assuming multiple needs stored as array

            // Previous School
            $table->string('last_grade_level_completed')->nullable();
            $table->string('last_school_attended')->nullable();
            $table->string('last_school_year_completed')->nullable();
            $table->string('school_id')->nullable();
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
