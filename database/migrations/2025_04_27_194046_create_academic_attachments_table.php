<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_attachments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('academic_qualification_id'); // Foreign key
            $table->string('file_path'); // Path to the uploaded file
            $table->string('attachment_type'); // Use a string to represent attachment type
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('academic_qualification_id')->references('id')->on('academic_qualifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_attachments');
    }
}
