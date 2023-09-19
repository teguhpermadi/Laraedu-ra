<?php

use App\Models\User;
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
        Schema::create('userables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('userable_id')->nullable();
            $table->string('userable_type')->nullable();
            $table->timestamps();

            $table->unique(['userable_id', 'userable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userables');
    }
};
