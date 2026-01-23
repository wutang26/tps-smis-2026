<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('m_p_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->unsignedInteger('added_by');
            $table->timestamp('arrested_at');
            $table->integer('days');
            $table->timestamp('released_at')->nullable();
            $table->string('description');
            $table->timestamps();
            //$table->foreign('student_id')->references('id')->on('students')->onupdate('update')->ondelete('null');
           // $table->foreign('added_by')->references('id')->on('user')->onupdate('update')->ondelete('null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_p_s');
    }
};
