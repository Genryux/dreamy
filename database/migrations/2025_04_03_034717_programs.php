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
        // Programs Table
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., HUMSS
            $table->string('name'); // e.g., Humanities and Social Sciences
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
