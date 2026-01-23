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
        Schema::create('time_sheet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('tasks');
            $table->string('description', 600);
            $table->enum('status', ['pending', 'approved','rejected'])->default('pending');
            $table->integer('hours')->nullable();
            $table->date('date');   
            $table->unsignedBigInteger('approved_by')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_sheet');
    }
};
