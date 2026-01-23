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
        Schema::create('semester_exams', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Link to courses with cascading delete
            $table->foreignId('semester_id')->constrained()->onDelete('cascade'); // Link to semesters with cascading delete
            $table->date('exam_date')->nullable(); // Nullable exam date
            $table->integer('max_score')->unsigned(); // Ensure max score is always positive
            $table->foreignId('session_programme_id')->constrained('session_programmes')->onDelete('cascade'); // Link to session programmes with cascading delete
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->nullable(true)->useCurrentOnUpdate();

            // Ensure that a course does not have multiple configurations (max_score) for the same semester and session programme.
            $table->unique(['course_id', 'semester_id', 'session_programme_id'], 'unique_semester_exam_configuration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_exams');
    }
};
