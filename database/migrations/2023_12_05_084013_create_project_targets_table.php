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
        Schema::create('project_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_grade_id')->references('id')->on('project_grades')->cascadeOnUpdate();
            $table->foreignId('target_id')->references('id')->on('targets')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_targets');
    }
};
