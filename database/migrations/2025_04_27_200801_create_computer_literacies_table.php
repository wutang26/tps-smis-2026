<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComputerLiteraciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computer_literacies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key linking to users table
            $table->string('skill'); // Specific skill, e.g., "Microsoft Excel", "Python"
            $table->string('proficiency'); // Proficiency level, e.g., "Beginner", "Intermediate", "Advanced"
            $table->string('certifications')->nullable(); // Certifications related to the skill
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
        Schema::dropIfExists('computer_literacies');
    }
}

