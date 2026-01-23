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
        Schema::create('attendences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('platoon_id');
            $table->unsignedBigInteger('attendenceType_id');
            $table->integer('present');
            $table->integer('sentry')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('adm')->nullable();
            $table->integer('safari')->nullable();
            $table->integer('mess')->nullable();
            $table->integer('off')->nullable();
            $table->integer('female');
            $table->integer('male');
            $table->integer('total');
            $table->string('absent_student_ids')->nullable();
            $table->string('safari_student_ids')->nullable();
            $table->string('sentry_student_ids')->nullable();
            $table->string('adm_student_ids')->nullable();
            $table->string('mess_student_ids')->nullable();
            $table->string('off_student_ids')->nullable();
            $table->timestamps();

            $table->foreign('attendenceType_id')->references('id')->on('attendence_types')->onupdate('update')->ondelete('null');
            $table->foreign('platoon_id')->references('id')->on('platoons')->onupdate('update')->ondelete('null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendences');
    }
};
