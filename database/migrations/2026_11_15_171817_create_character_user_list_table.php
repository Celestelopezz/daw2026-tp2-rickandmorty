<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('character_user_list', function (Blueprint $table) {
            $table->foreignId('user_list_id')->constrained('user_lists')->cascadeOnDelete(); // los tipos tiene que coincidir
            $table->unsignedInteger('character_id');

            $table->primary(['user_list_id', 'character_id']); // puede estas en diferentes listas, pero no repetido en la mmisma

            $table->foreign('character_id')->references('id')->on('characters')->cascadeOnDelete(); // si se borran las listas, se borran los pivotes tam.
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_user_list');
    }
};
