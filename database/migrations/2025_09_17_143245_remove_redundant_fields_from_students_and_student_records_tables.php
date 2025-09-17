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
        // Remove redundant fields from students table
        Schema::table('students', function (Blueprint $table) {
            // Remove fields that are duplicated in users table
            $table->dropColumn([
                'first_name',
                'last_name',
                'email_address'
            ]);
            
            // Remove fields that are duplicated in student_records table
            $table->dropColumn([
                'age',
                'gender',
                'contact_number'
            ]);
        });

        // Remove redundant fields from student_records table
        Schema::table('student_records', function (Blueprint $table) {
            // Remove fields that are duplicated in users table
            $table->dropColumn([
                'first_name',
                'last_name',
                'email'
            ]);
            
            // Remove fields that are duplicated in students table
            $table->dropColumn([
                'grade_level',
                'program'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore students table fields
        Schema::table('students', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email_address')->nullable();
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('contact_number')->nullable();
        });

        // Restore student_records table fields
        Schema::table('student_records', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('grade_level')->nullable();
            $table->string('program')->nullable();
        });
    }
};