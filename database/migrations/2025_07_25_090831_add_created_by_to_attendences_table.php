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
        Schema::table('attendences', function (Blueprint $table) {
            $table->unsignedBigInteger('recorded_by')->nullable()->after('off_student_ids');
            $table->date('date')->nullable()->after('recorded_by');
            $table->foreign('recorded_by')->references('id')->on('users')->onupdate('update')->ondelete('null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendences', function (Blueprint $table) {
            $table->dropColumn('recorded_by');
            $table->dropColumn('date');
        });
    }
};
