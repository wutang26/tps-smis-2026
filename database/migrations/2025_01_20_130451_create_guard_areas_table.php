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
        Schema::create('guard_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('campus_id');
            $table->unsignedBigInteger('added_by');
            $table->json('beat_exception_ids')->nullable();
            $table->json('beat_time_exception_ids')->nullable();
            $table->integer('number_of_guards')->default(2);
            // $table->foreign('campus_id')->references('id')->on('campuses')->onupdate('update')->ondelete('null');
            // $table->foreign('created_by')->references('id')->on('users')->onupdate('update')->ondelete('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guard_areas');
    }
};
