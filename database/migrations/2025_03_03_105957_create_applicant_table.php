<?php

use App\Models\AcademicTerms;
use App\Models\EnrollmentPeriod;
use App\Models\Program;
use App\Models\Track;
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
            $table->foreignIdFor(Track::class)->nullable()->cascadeOnDelete();
            $table->foreignIdFor(Program::class)->nullable()->nullOnDelete();
            $table->string('applicant_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('application_status', ['Pending', 'Accepted', 'Pending-Documents', 'Rejected', 'Completed-Failed', 'Officially Enrolled'])->nullable();
            $table->date('accepted_at')->nullable();
            $table->string('accepted_by', 100)->nullable();
            $table->string('rejection_reason')->nullable();
            $table->text('rejection_remarks')->nullable();
            $table->date('rejected_at')->nullable();
            $table->string('rejected_by', 100)->nullable();
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
