<?php

use App\Models\Program;
use App\Models\Section;
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Section::class)->nullable()->constrained()->onDelete('set null');
            $table->foreignIdFor(Track::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Program::class)->nullable()->constrained()->nullOnDelete();
            $table->string('lrn')->nullable();
            $table->string('grade_level')->nullable();
            $table->enum('academic_status', ['Passed', 'Failed', 'Completed'])->nullable();
            $table->date('enrollment_date')->nullable();
            $table->enum('status', ['Officially Enrolled', 'Dropped', 'Transferred', 'Graduated'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
