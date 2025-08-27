<?php

use App\Models\Program;
use App\Models\Teacher;
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
        // Sections Table
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Program::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Teacher::class)->nullable()->constrained()->nullOnDelete();
            $table->string('year_level')->nullable();
            $table->string('room')->nullable();
            $table->unsignedInteger('total_enrolled_students')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
