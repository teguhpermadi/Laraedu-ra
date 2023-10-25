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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->nullable();
            $table->string('nis')->nullable();
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->unique(['nisn','nis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
