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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('force_number')->unique()->nullable();
            $table->string('rank');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('vitengo_id')->nullable();
            $table->char('gender');
            $table->char('blood_group')->nullable();
            $table->string('phone')->nullable();
            $table->string('nin')->unique();
            $table->string('dob');
            $table->string('education_level')->nullable();
            $table->string('home_region')->nullable();
            $table->foreignId('company_id')->constrained('companies'); 
            $table->string('photo')->nullable();
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->char('platoon')->nullable();
            $table->integer('beat_status')->default(0);
            $table->integer('beat_round')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('vitengo_id')->references('id')->on('vitengos')->onUpdate('cascade')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent()->nullable(false);
            $table->timestamp('updated_at')->nullable(true)->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
