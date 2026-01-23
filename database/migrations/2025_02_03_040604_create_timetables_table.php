<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // Foreign key
           // $table->string('company'); // HQ, A, B, C
            $table->string('day'); // Monday - Sunday
            $table->string('time_slot'); // E.g., "08:00 - 10:00"
            $table->string('activity'); // E.g., "Parade", "Drills", etc.
            $table->string('venue');
            $table->string('instructor');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('timetables');
    }
};
