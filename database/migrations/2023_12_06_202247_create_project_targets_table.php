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
            $table->foreignId('project_id')->references('id')->on('projects')->cascadeOnDelete();
            $table->string('phase');
            $table->foreignId('dimention_id')->references('id')->on('dimentions')->cascadeOnDelete();
            $table->foreignId('element_id')->references('id')->on('elements')->cascadeOnDelete();
            $table->foreignId('sub_element_id')->references('id')->on('sub_elements')->cascadeOnDelete();
            $table->foreignId('value_id')->references('id')->on('values')->cascadeOnDelete();
            $table->foreignId('sub_value_id')->references('id')->on('sub_values')->cascadeOnDelete();
            $table->foreignId('target_id')->references('id')->on('targets')->cascadeOnDelete();
            // $table->timestamps();
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
