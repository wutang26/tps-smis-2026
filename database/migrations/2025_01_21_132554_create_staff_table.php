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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('forceNumber')->unique()->nullable();
            $table->string('rank');
            $table->string('nin')->nullable();
            $table->string('firstName');
            $table->string('middleName');
            $table->string('lastName');
            $table->string('gender');
            $table->date('DoB')->nullable();
            $table->string('maritalStatus')->nullable();
            $table->string('religion')->nullable();
            $table->string('tribe')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('email');
            $table->string('currentAddress')->nullable();
            $table->string('permanentAddress')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->string('designation')->nullable();
            $table->string('educationLevel')->nullable();
            $table->string('contractType')->nullable();
            $table->date('joiningDate')->nullable();
            $table->string('location')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('profile_complete')->default(false); // Profile completeness status
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->nullable(true)->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};