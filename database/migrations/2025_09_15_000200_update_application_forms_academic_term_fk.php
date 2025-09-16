<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_forms', function (Blueprint $table) {
            // Ensure academic_terms_id has proper foreign key constraint
            // (it might already exist but let's make sure)
            if (!Schema::hasColumn('application_forms', 'academic_terms_id')) {
                $table->foreignId('academic_terms_id')->nullable()->constrained('academic_terms')->nullOnDelete();
            }
            
            // Add index for performance when filtering by term
            if (!Schema::hasIndex('application_forms', ['academic_terms_id'])) {
                $table->index('academic_terms_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('application_forms', function (Blueprint $table) {
            $table->dropIndex(['academic_terms_id']);
            $table->dropForeign(['academic_terms_id']);
        });
    }
};
