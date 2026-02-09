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
        Schema::create('beat_assignment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('area_id');
            $table->string('reason'); // strict eligibility / dynamic fallback / emergency fill
            $table->integer('beat_round')->default(0);
            $table->timestamp('last_assigned_at')->nullable();
            $table->date('date'); // date of assignment
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('guard_area_id')->references('id')->on('guard_areas')->onDelete('cascade');
            $table->foreign('patrol_area_id')->references('id')->on('patrol_areas')->onDelete('cascade');
 
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beat_assignment_logs');
    }
};
