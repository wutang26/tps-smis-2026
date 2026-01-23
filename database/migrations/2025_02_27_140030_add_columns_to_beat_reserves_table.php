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
        Schema::table('beat_reserves', function (Blueprint $table) {
            $table->unsignedBigInteger('replaced_student_id')->after('student_id')->nullable();
            $table->string('replacement_reason')->after('replaced_student_id')->nullable();
            // $table->integer('beat_round')->after('student_id')->nullable();
            $table->boolean('released')->after('replacement_reason')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beat_reserves', function (Blueprint $table) {
            $table->dropColumn('replaced_student_id');
            // $table->dropColumn('beat_round');
            $table->dropColumn('replacement_reason');
            $table->dropColumn('released');
        });
    }
};
