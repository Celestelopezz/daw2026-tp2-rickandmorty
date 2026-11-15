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
        Schema::create('episode_user_list', function (Blueprint $table) {
            $table->foreignId('user_list_id')->constrained('user_lists')->cascadeOnDelete();
            $table->unsignedInteger('episode_id');

            $table->primary(['user_list_id', 'episode_id']);

            $table->foreign('episode_id')->references('id')->on('episodes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_user_list');
    }
};
