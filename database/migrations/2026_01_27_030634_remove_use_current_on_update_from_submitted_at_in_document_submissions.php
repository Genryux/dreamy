<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to modify the column's ON UPDATE behavior
        DB::statement('ALTER TABLE document_submissions MODIFY COLUMN submitted_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to having ON UPDATE CURRENT_TIMESTAMP
        DB::statement('ALTER TABLE document_submissions MODIFY COLUMN submitted_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
    }
};
