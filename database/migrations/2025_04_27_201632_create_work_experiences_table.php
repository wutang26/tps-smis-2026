<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key linking to users table
            $table->string('institution'); // Institution or organization name
            $table->string('address'); // Address of the institution
            $table->string('job_title'); // Job title
            //$table->string('position'); // Position in the job (e.g., Manager, Intern)
            $table->json('duties'); // Duties or responsibilities
            $table->string('supervisor_name')->nullable(); // Supervisor's name
            $table->string('supervisor_phone_number')->nullable(); // Supervisor's phone number
            $table->string('supervisor_address')->nullable(); // Supervisor's address
            $table->string('start_date'); // Start date
            $table->string('end_date')->nullable(); // End date (optional for current job)
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
        Schema::dropIfExists('work_experiences');
    }
}
