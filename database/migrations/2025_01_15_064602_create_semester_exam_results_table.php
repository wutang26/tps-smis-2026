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
        Schema::create('semester_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade'); // Cascade delete when a student is deleted
            $table->foreignId('semester_exam_id')->constrained('semester_exams')->onDelete('cascade'); // Cascade delete when an exam is deleted
            $table->integer('score')->unsigned(); // Positive scores only
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->nullable(true)->useCurrentOnUpdate();

            // Unique constraint: Ensure a student can have only one result per exam.
            $table->unique(['student_id', 'semester_exam_id'], 'unique_student_semester_exam_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_exam_results');
    }
};
