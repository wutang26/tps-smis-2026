<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Used to determine the final grade based on the total score.
    public function up(): void
    {
        Schema::create('grade_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grading_system_id')->constrained()->onDelete('cascade'); // foreign key reference to grading_systems table
            $table->string('grade'); // e.g., A, B, C, etc.
            $table->string('grade_point'); // e.g., A, B, C, etc.
            $table->float('min_score'); // minimum score for the grade
            $table->float('max_score'); // maximum score for the grade
            $table->text('remarks'); // description for the grade
            $table->text('class_award')->nullable(); // Class of award for the grade
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_mappings');
    }
};
