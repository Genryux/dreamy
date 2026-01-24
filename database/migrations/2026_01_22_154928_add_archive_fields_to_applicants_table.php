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
            $table->boolean('is_archived')->default(false)->after('application_status');
            $table->string('archive_type')->nullable()->after('is_archived')->comment('manual or period_expired');
            $table->timestamp('archived_at')->nullable()->after('archive_type');
            $table->unsignedBigInteger('archived_by')->nullable()->after('archived_at');
            $table->text('archive_reason')->nullable()->after('archived_by');
            $table->timestamp('restored_at')->nullable()->after('archive_reason');
            $table->unsignedBigInteger('restored_by')->nullable()->after('restored_at');
            
            $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('restored_by')->references('id')->on('users')->nullOnDelete();
            
            $table->index(['is_archived', 'application_status'], 'idx_archive_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropForeign(['archived_by']);
            $table->dropForeign(['restored_by']);
            $table->dropIndex('idx_archive_status');
            
            $table->dropColumn([
                'is_archived',
                'archive_type',
                'archived_at',
                'archived_by',
                'archive_reason',
                'restored_at',
                'restored_by'
            ]);
        });
    }
};
