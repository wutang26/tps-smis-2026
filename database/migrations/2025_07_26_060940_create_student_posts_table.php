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
        Schema::create('student_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('post_id');
            $table->string('region');
            $table->string('district')->nullable();
            $table->string('unit')->nullable();
            $table->string('office')->nullable();
            $table->timestamps();     
            $table->foreign('post_id')->references('id')->on('posts')->onupdate('update')->ondelete('null');       
            $table->foreign('student_id')->references('id')->on('students')->onupdate('update')->ondelete('null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_posts');
    }
};
