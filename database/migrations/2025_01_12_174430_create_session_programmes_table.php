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
        Schema::create('session_programmes', function (Blueprint $table) {
            $table->id();
            $table->string('session_programme_name', 200)->unique();
            $table->text('description');
            $table->string('year', 20);
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->foreignId('programme_id')->constrained('programmes');
            $table->boolean('is_current');
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_programmes');
    }
};
