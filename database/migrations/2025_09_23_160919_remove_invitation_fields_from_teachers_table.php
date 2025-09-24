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
            // Remove invitation fields that are now handled by the User model
            // Only drop columns if they exist
            if (Schema::hasColumn('teachers', 'invitation_token')) {
                $table->dropColumn('invitation_token');
            }
            if (Schema::hasColumn('teachers', 'invitation_sent_at')) {
                $table->dropColumn('invitation_sent_at');
            }
            if (Schema::hasColumn('teachers', 'invitation_accepted_at')) {
                $table->dropColumn('invitation_accepted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add back invitation fields if needed
            $table->string('invitation_token')->unique()->nullable()->after('status');
            $table->timestamp('invitation_sent_at')->nullable()->after('invitation_token');
            $table->timestamp('invitation_accepted_at')->nullable()->after('invitation_sent_at');
        });
    }
};