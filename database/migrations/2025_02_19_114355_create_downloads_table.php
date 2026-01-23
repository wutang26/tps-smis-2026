<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path'); // Path of the file
            $table->string('category')->nullable(); // Notice, Report, Assignment, etc.
            $table->unsignedBigInteger('uploaded_by'); // Who uploaded it
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('downloads');
    }
};
