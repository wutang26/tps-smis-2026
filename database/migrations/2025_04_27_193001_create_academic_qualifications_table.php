<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('education_level'); // e.g., High School, Bachelor's, Master's
            $table->string('programme_name'); // e.g., Computer Science
            $table->string('place'); // City or Country
            $table->string('institution_name'); // e.g., University of Kilimanjaro
            $table->date('date_from'); // Start date
            $table->date('date_to'); // End date
            $table->string('grade')->nullable(); // Optional field for grades
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_qualifications');
    }
}
