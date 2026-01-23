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
        Schema::create('programme_course_semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->enum('course_type', ['core', 'minor', 'optional']);
            $table->integer('credit_weight');
            $table->unsignedBigInteger('session_programme_id')->constrained();
            $table->unsignedBigInteger('created_by')->constrained();
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->nullable(true)->useCurrentOnUpdate();

            // Adding a unique constraint on the combination of programme_id, course_id, and session_programme_id  with a custom name
            $table->unique(['programme_id', 'course_id', 'session_programme_id'], 'prog_course_session_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_course_semesters');
    }
};