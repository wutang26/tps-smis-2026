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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_programme_id')->unique();
            $table->enum('status',['pending',['published']])->default('pending');
            $table->unsignedBigInteger('published_by')->nullable();
            $table->timestamp('published_at');
            $table->timestamps();

            $table->foreign('published_by')->references('id')->on('users')->onupdate('update')->ondelete('null');
            $table->foreign('session_programme_id')->references('id')->on('session_programmes')->onupdate('update')->ondelete('null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
