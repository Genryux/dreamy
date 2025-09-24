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
        Schema::table('users', function (Blueprint $table) {
            // Add invitation fields
            $table->string('invitation_token')->unique()->nullable()->after('pin_setup_at');
            $table->timestamp('invitation_sent_at')->nullable()->after('invitation_token');
            $table->timestamp('invitation_accepted_at')->nullable()->after('invitation_sent_at');
            $table->string('invitation_role')->nullable()->after('invitation_accepted_at');
            $table->unsignedBigInteger('invited_by')->nullable()->after('invitation_role');
            $table->string('status')->default('active')->after('invited_by');

            // Add foreign key constraint
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('set null');

            // Add index for better performance
            $table->index(['status', 'invitation_role']);
            $table->index(['invitation_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['invited_by']);

            // Drop indexes
            $table->dropIndex(['status', 'invitation_role']);
            $table->dropIndex(['invitation_token']);

            // Drop new columns
            $table->dropColumn([
                'invitation_token',
                'invitation_sent_at',
                'invitation_accepted_at',
                'invitation_role',
                'invited_by',
                'status'
            ]);
        });
    }
};