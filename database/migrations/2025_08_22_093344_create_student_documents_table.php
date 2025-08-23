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
        Schema::create('student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Students::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Documents::class)
                ->constrained()
                ->onDelete('cascade');
            $table->date('submit_before')->nullable();
            $table->enum('status', ['not-submitted', 'submitted', 'verified', 'rejected'])->default('not-submitted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_documents');
    }
};
