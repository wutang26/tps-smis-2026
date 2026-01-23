<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionalCourseEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::create('optional_course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->date('enrollment_date'); // enrollment date
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('optional_course_enrollments');
    }
}
