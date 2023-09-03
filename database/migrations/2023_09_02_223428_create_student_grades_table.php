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
        Schema::create('student_grade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            $table->foreignId('grade_id')->references('id')->on('grades')->onDelete('cascade');
            $table->foreignId('student_id')->references('id')->on('students')->onDelete('cascade');
            // $table->timestamps();
            
            // Tambahkan indeks unik pada kombinasi ketiga kolom tersebut
            $table->unique(['academic_year_id', 'grade_id', 'student_id'], 'student_grade_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
