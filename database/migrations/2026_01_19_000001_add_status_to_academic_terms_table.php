<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('academic_terms', function (Blueprint $table) {
            $table->enum('status', ['Upcoming', 'Ongoing', 'Closing'])
                ->default('Upcoming')
                ->after('is_active');
        });

        // Backfill existing terms: active -> Ongoing, inactive -> Upcoming
        DB::table('academic_terms')
            ->where('is_active', true)
            ->update(['status' => 'Ongoing']);

        DB::table('academic_terms')
            ->where('is_active', false)
            ->whereNull('status')
            ->update(['status' => 'Upcoming']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_terms', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
