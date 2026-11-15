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
        Schema::create('characters', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name');
            $table->string('status', 50)->nullable();
            $table->string('species', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('image')->nullable();
            $table->string('origin')->nullable();
            $table->unsignedInteger('location_id')->nullable();

            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
