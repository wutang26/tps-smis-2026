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
        Schema::create('patrol_areas', function (Blueprint $table) {
            $table->id();
            $table->string('start_area');
            $table->string('end_area');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('campus_id');
            $table->unsignedBigInteger('added_by');
            $table->integer('number_of_guards');
            $table->json('beat_exception_ids')->nullable();
            $table->json('beat_time_exception_ids')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onupdate('update')->ondelete('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrol_areas');
    }
};
