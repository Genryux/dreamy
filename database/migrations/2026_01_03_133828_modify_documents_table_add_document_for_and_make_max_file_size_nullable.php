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
        Schema::table('documents', function (Blueprint $table) {
            // Make max_file_size nullable
            $table->integer('max_file_size')->nullable()->change();
            
            // Add document_for field (regular, transferee, or both)
            $table->string('document_for')->default('both')->after('max_file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Revert max_file_size to not nullable
            $table->integer('max_file_size')->nullable(false)->change();
            
            // Drop document_for field
            $table->dropColumn('document_for');
        });
    }
};
