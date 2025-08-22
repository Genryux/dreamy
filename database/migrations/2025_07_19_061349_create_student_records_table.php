<?php

use App\Models\Students;
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
        Schema::create('student_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Students::class)->constrained()->onDelete('cascade');

            // Personal Info
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable(); //
            $table->integer('age')->nullable();
            $table->string('place_of_birth')->nullable();

            // Contact Info
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();

            // Address
            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();

            // Parent/Guardian Info
            $table->string('father_name')->nullable();
            $table->string('father_contact_number')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_contact_number')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact_number')->nullable();

            // Academic Info

            $table->string('grade_level')->nullable();
            $table->string('program')->nullable();
            $table->string('current_school')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('school_contact_info')->nullable();
            $table->string('acad_term_applied')->nullable();
            $table->string('semester_applied')->nullable();
            $table->dateTime('admission_date')->nullable();

            // Additional Info
            $table->boolean('has_special_needs')->default(false)->nullable();
            $table->boolean('belongs_to_ip')->default(false)->nullable();
            $table->boolean('is_4ps_beneficiary')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};
