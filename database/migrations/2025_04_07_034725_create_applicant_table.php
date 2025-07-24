<?php

use App\Models\AcademicTerms;
use App\Models\EnrollmentPeriod;
use App\Models\User;
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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('applicant_id')->unique();

            //academic
            $table->string('preferred_sched');
            $table->string('lrn');
            $table->string('grade_level');
            $table->string('primary_track');
            $table->string('secondary_track');

            //personal info
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->date('birthdate');
            $table->integer('age');
            $table->text('place_of_birth');
            $table->string('mother_tongue')->nullable();
            $table->boolean('belongs_to_ip')->default(false);
            $table->boolean('is_4ps_beneficiary')->default(false);

            //permanent address
            $table->string('perm_house_no')->nullable();
            $table->string('perm_street')->nullable();
            $table->string('perm_barangay');
            $table->string('perm_city');
            $table->string('perm_province');
            $table->string('perm_country');
            $table->string('perm_zip_code');

            //current address
            $table->string('cur_house_no')->nullable();
            $table->string('cur_street')->nullable();
            $table->string('cur_barangay');
            $table->string('cur_city');
            $table->string('cur_province');
            $table->string('cur_country');
            $table->string('cur_zip_code');

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

            $table->boolean('has_special_needs')->default(false);
            $table->string('special_needs')->nullable();

            $table->boolean('is_returning')->default(false);
            $table->string('last_grade_level_completed')->nullable();
            $table->string('last_school_attended')->nullable();
            $table->string('last_school_year_completed')->nullable();
            $table->string('school_id')->nullable();


            $table->string('application_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
