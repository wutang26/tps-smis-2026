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
        Schema::create('grading_systems', function (Blueprint $table) {
            $table->id();
            $table->string('system_name'); // e.g., Letter Grade, Percentage, GPA
            $table->text('description')->nullable(); // optional description of the grading system
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_systems');
    }
};
