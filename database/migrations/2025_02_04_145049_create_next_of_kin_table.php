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
        Schema::create('next_of_kin', function (Blueprint $table) {
            $table->id();
            $table->string('nextofkinFullname');
            $table->string('nextofkinRelationship');
            $table->string('nextofkinPhoneNumber')->nullable();
            $table->string('nextofkinPhysicalAddress')->nullable();
            $table->unsignedBigInteger('staff_id')->nullable(); // assuming there's a staff associated with next of kin
            $table->unsignedBigInteger('student_id')->nullable(); // assuming there's a student associated with next of kin
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->nullable(true)->useCurrentOnUpdate();

            // Adding foreign key constraints with cascade delete
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('next_of_kin');
    }
};
