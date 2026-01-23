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
        Schema::create('beats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beatType_id');
            $table->unsignedBigInteger('guardArea_id')->nullable();              //For Guards
            $table->unsignedBigInteger('patrolArea_id')->nullable();        //For Patrols
            $table->json('student_ids');
            $table->date('date');
            $table->time('start_at');
            $table->time('end_at')->nullable();
            $table->boolean('status')->nullable();
            $table->foreign('guardArea_id')->references('id')->on('guard_areas')->onupdate('update')->ondelete('null');
            $table->foreign('patrolArea_id')->references('id')->on('patrol_areas')->onupdate('update')->ondelete('null');
            $table->foreign('beatType_id')->references('id')->on('beat_types')->onupdate('update')->ondelete('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beats');
    }
};
