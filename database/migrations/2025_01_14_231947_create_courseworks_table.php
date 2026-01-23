<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courseworks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters');      // links to 'semesters'
            $table->foreignId('assessment_type_id')->constrained('assessment_types');  // assessment type (as a foreign key)          
            $table->string('coursework_title');  // e.g., "Assignment One", "Quiz 1"
            $table->integer('max_score');
            $table->date('due_date')->nullable();
            $table->foreignId('session_programme_id')->constrained('session_programmes');  // links to session_programmes (which in turn relates to programmes)
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Unique constraint ensures that for a given course, session programme, semester, and assessment type,
            // only one coursework configuration exists.
            $table->unique(
                ['course_id', 'session_programme_id', 'semester_id', 'assessment_type_id', 'coursework_title'],
                'unique_course_assessment_config'
            );

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courseworks');
    }
};
