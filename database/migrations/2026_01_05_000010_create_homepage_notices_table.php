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
        Schema::create('homepage_notices', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('bg_color')->default('#C8A165');
            $table->string('text_color')->default('#FFFFFF');
            $table->string('link_url')->nullable();
            $table->boolean('is_scrolling')->default(true);
            $table->boolean('is_dismissible')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_notices');
    }
};
