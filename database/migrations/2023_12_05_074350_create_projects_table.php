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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('project_theme_id')->references('id')->on('project_themes')->cascadeOnUpdate();
            $table->foreignId('academic_year_id')->references('id')->on('academic_years')->cascadeOnUpdate();
            $table->foreignId('grade_id')->references('id')->on('grades')->cascadeOnUpdate();
            $table->foreignId('teacher_id')->references('id')->on('teachers')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
