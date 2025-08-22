<?php

use App\Models\AcademicTerms;
use App\Models\Applicant;
use App\Models\ApplicantDocuments;
use App\Models\Applicants;
use App\Models\Documents;
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
        Schema::create('document_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AcademicTerms::class)->constrained();
            $table->foreignIdFor(EnrollmentPeriod::class)->constrained();
            $table->foreignIdFor(Applicants::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Documents::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(ApplicantDocuments::class)->constrained()->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_submissions');
    }
};
