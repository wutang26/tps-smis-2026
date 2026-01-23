<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshopsAndTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshops_and_trainings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key linking to the users table
            $table->string('training_name'); // Training or workshop name
            $table->text('training_description')->nullable(); // Description of the training/workshop
            $table->string('institution'); // Name of the institution
            $table->date('start_date'); // Start date
            $table->date('end_date'); // End date
            $table->string('certificate')->nullable(); // Path to the optional certificate file
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
        Schema::dropIfExists('workshops_and_trainings');
    }
}
