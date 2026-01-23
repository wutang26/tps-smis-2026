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
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->date('date');
            // $table->string('date');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('attendenceType_id');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('reason');
            $table->enum('status',['pending','approved','rejected','closed'])->default('pending');
            $table->timestamps();
            $table->foreign('attendenceType_id')->references('id')->on('attendence_types')->onupdate('update')->ondelete('null');
            $table->foreign('company_id')->references('id')->on('companies')->onupdate('update')->ondelete('null');
            $table->foreign('requested_by')->references('id')->on('users')->onupdate('update')->ondelete('null');
            $table->foreign('approved_by')->references('id')->on('users')->onupdate('update')->ondelete('null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};
