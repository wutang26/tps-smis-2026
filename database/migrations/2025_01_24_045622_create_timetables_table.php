<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimetablesTable extends Migration
{
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('company'); // HQ, A, B, C, PST
            $table->string('day'); 
            $table->string('venue');
            $table->string('subject');
            $table->string('teacher');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('timetables');
    }
}

