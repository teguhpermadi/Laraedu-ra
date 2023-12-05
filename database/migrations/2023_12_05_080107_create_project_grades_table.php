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
        Schema::create('project_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->references('id')->on('projects')->cascadeOnUpdate();
            $table->foreignId('academic_year_id')->references('id')->on('academic_years')->cascadeOnUpdate();
            $table->foreignId('grade_id')->references('id')->on('grades')->cascadeOnUpdate();
            $table->foreignId('teacher_id')->references('id')->on('teachers')->cascadeOnUpdate();
            $table->timestamps();

            $table->unique(['project_id', 'academic_year_id', 'grade_id'], 'project_grades_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_grades');
    }
};
