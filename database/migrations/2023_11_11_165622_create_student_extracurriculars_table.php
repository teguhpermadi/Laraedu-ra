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
        Schema::create('student_extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->references('id')->on('academic_years')->cascadeOnDelete();
            $table->foreignId('extracurricular_id')->references('id')->on('extracurriculars')->cascadeOnDelete();
            $table->foreignId('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->string('score')->default(0);
            $table->timestamps();

            $table->unique(['academic_year_id', 'student_id', 'extracurricular_id'], 'student_extracurricular_unique');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_extracurriculars');
    }
};
