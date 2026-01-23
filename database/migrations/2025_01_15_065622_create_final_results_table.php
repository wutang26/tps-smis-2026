<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * When calculating the final results:
     * Retrieve the student’s coursework and exam results using the student_id, course_id, and semester_id from the enrollments table.
     * Sum these scores to get the total score for the course.
     * Use the grading system to map the total score to a final grade.
     * So while the enrollments table is essential for linking students to their courses and semesters, the actual calculation of final results relies on the scores recorded in the coursework_results and semester_exam_results tables.
     */
    public function up(): void
    {
        Schema::create('final_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained(); 
            $table->foreignId('semester_id')->constrained(); 
            $table->foreignId('course_id')->constrained(); 
            $table->integer('total_score')->nullable(); 
            $table->string('grade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_results');
    }
};
