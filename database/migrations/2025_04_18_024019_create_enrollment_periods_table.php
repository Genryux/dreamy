<?php

use App\Models\AcademicTerms;
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
        Schema::create('enrollment_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AcademicTerms::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('application_start_date');
            $table->date('application_end_date');
            $table->integer('max_applicants');
            $table->string('status')->default('Ongoing'); // Ongoing, Paused, Ended
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_periods');
    }
};
