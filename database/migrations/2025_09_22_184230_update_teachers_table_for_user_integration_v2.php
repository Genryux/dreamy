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
        Schema::table('teachers', function (Blueprint $table) {
            // Add user_id column if it doesn't exist
            if (!Schema::hasColumn('teachers', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
            
            // Add employee_id column if it doesn't exist
            if (!Schema::hasColumn('teachers', 'employee_id')) {
                $table->string('employee_id')->nullable()->after('user_id');
            }
        });

        // Now update any existing teachers with empty employee_id
        DB::table('teachers')->where(function($query) {
            $query->whereNull('employee_id')->orWhere('employee_id', '');
        })->update(['employee_id' => DB::raw("CONCAT('TCH', LPAD(id, 4, '0'))")]);

        // Add foreign key constraint
        Schema::table('teachers', function (Blueprint $table) {
            if (!$this->foreignKeyExists('teachers', 'teachers_user_id_foreign')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });

        // Add unique constraint for employee_id
        Schema::table('teachers', function (Blueprint $table) {
            if (!$this->indexExists('teachers', 'teachers_employee_id_unique')) {
                $table->unique('employee_id');
            }
        });

        // Add index for better performance
        Schema::table('teachers', function (Blueprint $table) {
            if (!$this->indexExists('teachers', 'teachers_status_user_id_index')) {
                $table->index(['status', 'user_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop foreign key constraint
            if ($this->foreignKeyExists('teachers', 'teachers_user_id_foreign')) {
                $table->dropForeign(['user_id']);
            }
            
            // Drop index
            if ($this->indexExists('teachers', 'teachers_status_user_id_index')) {
                $table->dropIndex(['status', 'user_id']);
            }
            
            // Drop unique constraint
            if ($this->indexExists('teachers', 'teachers_employee_id_unique')) {
                $table->dropUnique(['employee_id']);
            }
            
            // Drop columns
            $table->dropColumn([
                'user_id',
                'employee_id'
            ]);
        });
    }

    private function foreignKeyExists($table, $key)
    {
        try {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = '{$table}' 
                AND CONSTRAINT_NAME = '{$key}'
            ");
            return count($foreignKeys) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function indexExists($table, $index)
    {
        try {
            $indexes = DB::select("
                SELECT INDEX_NAME 
                FROM information_schema.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = '{$table}' 
                AND INDEX_NAME = '{$index}'
            ");
            return count($indexes) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
};