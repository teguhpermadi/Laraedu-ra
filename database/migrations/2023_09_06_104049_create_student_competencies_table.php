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
        Schema::create('student_competencies', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('teacher_subject_id')->constrained()->onDelete('cascade');
            // $table->foreignId('competency_id')->constrained()->nullable()->onDelete('cascade');
            // $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_subject_id')->references('id')->on('teacher_subjects')->cascadeOnDelete();
            $table->foreignId('competency_id')->references('id')->on('competencies')->cascadeOnDelete();
            $table->foreignId('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->integer('score_skill')->default(0);
            // $table->timestamps();

            $table->unique(['student_id', 'competency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_competencies');
    }
};
