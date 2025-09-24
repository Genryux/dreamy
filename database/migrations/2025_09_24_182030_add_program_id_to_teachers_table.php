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
        Schema::table('teachers', function (Blueprint $table) {
            // Add program_id foreign key
            $table->unsignedBigInteger('program_id')->nullable()->after('contact_number');
            
            // Add foreign key constraint
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('set null');
            
            // Add index for better performance
            $table->index('program_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['program_id']);
            
            // Drop index
            $table->dropIndex(['program_id']);
            
            // Drop column
            $table->dropColumn('program_id');
        });
    }
};