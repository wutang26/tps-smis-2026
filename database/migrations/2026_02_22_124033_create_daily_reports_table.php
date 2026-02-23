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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->foreignId('reported_by')->constrained('users');
            $table->string('department')->nullable();
            $table->text('repeated_cases')->nullable();
            $table->text('overloaded_cases')->nullable();
            $table->text('sick_cases')->nullable();
            $table->text('last_assigned_date')->nullable();
            $table->text('vitengo_cases')->nullable();
            $table->text('challenges')->nullable();
            $table->text('suggestions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
