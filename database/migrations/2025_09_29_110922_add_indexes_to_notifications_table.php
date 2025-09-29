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
        Schema::table('notifications', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index(['notifiable_id', 'notifiable_type'], 'notifications_notifiable_index');
            $table->index('read_at', 'notifications_read_at_index');
            $table->index('created_at', 'notifications_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_index');
            $table->dropIndex('notifications_read_at_index');
            $table->dropIndex('notifications_created_at_index');
        });
    }
};