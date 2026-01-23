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
        Schema::create('staff_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->enum('previous_status',['active','leave','safari','secondment']);
            $table->enum('current_status',['active','leave','safari','secondment']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_statuses');
    }
};
