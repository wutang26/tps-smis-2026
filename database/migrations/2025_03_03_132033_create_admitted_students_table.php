<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmittedStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admitted_students', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('student_id')->constrained('students');
            $table->string('registration_number')->unique();
            $table->foreignId('programme_id')->constrained('programmes');
            $table->foreignId('study_level_id')->constrained('study_levels');
            $table->date('admitted_date');
            $table->date('completion_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admitted_students');
    }
}
