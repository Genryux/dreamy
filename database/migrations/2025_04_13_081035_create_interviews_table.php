<?php

use App\Models\Applicant;
use App\Models\Applicants;
use App\Models\Teacher;
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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Applicants::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Teacher::class)->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('location')->nullable();
            $table->text('add_info')->nullable();
            $table->enum('status', ['Scheduled', 'Taking-Exam', 'Exam-Completed', 'Exam-Passed', 'Exam-Failed'])->nullable();
            $table->text('remarks')->nullable();
            $table->string('recorded_by', 100)->nullable();
            $table->date('recorded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
