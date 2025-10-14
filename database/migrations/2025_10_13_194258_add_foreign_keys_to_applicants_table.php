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
        Schema::table('applicants', function (Blueprint $table) {
            // Add foreign key constraints if they don't exist
            $table->foreign('track_id')->references('id')->on('tracks')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['track_id']);
            $table->dropForeign(['program_id']);
        });
    }
};
