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
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('application_status', ['Pending', 'Accepted', 'Scheduled', 'Pending-Documents', 'Rejected', 'Officially Enrolled'])->nullable();
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
