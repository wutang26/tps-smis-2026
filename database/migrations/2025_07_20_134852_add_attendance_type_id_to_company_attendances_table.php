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
        Schema::table('company_attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('attendenceType_id')->after('company_id')->nullable();
            // $table->dropUnique(['company_id', 'date']);

            // Add the new one with attendanceType_id
            $table->unique(['company_id', 'date', 'attendenceType_id']);
            $table->foreign('attendenceType_id')->references('id')->on('attendence_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_attendances', function (Blueprint $table) {
            $table->dropForeign(['attendenceType_id']);
        });
    }
};
