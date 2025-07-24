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
            $table->date('birthdate');
            $table->integer('age');
            $table->string('place_of_birth');

            // Contact Info
            $table->string('email')->nullable();
            $table->text('current_address');
            $table->text('permanent_address');
            $table->string('contact_number');

            // Parent/Guardian Info
            $table->string('father_name')->nullable();
            $table->string('father_contact_number')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_contact_number')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact_number')->nullable();

            // Academic Info
            $table->string('semester')->nullable();
            $table->string('current_school')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('school_contact_info')->nullable();

            // Additional Info
            $table->boolean('has_special_needs')->default(false);
            $table->boolean('belongs_to_ip')->default(false);
            $table->boolean('is_4ps_beneficiary')->default(false);
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
