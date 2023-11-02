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
        Schema::create('competencies', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('teacher_subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_subject_id')->references('id')->on('teacher_subjects')->cascadeOnDelete();
            $table->enum('category', ['pengetahuan', 'keterampilan'])->nullable();
            $table->string('code')->unique()->nullable();
            $table->string('description');
            $table->string('passing_grade')->default(0);
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competencies');
    }
};
