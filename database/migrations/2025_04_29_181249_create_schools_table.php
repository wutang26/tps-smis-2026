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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('education_level_id');
            $table->string('name');
            $table->string('admission_year')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('duration')->nullable();
            $table->string('country')->nullable();
            $table->string('award')->nullable();
            $table->string('village')->nullable();
            $table->string('district')->nullable();
            $table->string('venue')->nullable();
            $table->timestamps();
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
