<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefereesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key linking to users table
            $table->string('referee_fullname'); // Full name of the referee
            $table->string('title')->nullable(); // Title of the referee (e.g., "Dr.", "Mr.", "Ms.")
            $table->string('organization')->nullable(); // Organization the referee is affiliated with
            $table->string('email_address'); // Referee's email address
            $table->string('phone_number'); // Referee's phone number
            $table->string('address')->nullable(); // Address or location of the referee
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
        Schema::dropIfExists('referees');
    }
}

