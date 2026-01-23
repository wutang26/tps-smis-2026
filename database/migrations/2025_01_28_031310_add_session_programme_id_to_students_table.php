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
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('session_programme_id')->after('user_id')->nullable();
            $table->foreign('session_programme_id')->references('id')->on('session_programmes')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'sessionprogramme_id')) {
                $table->dropForeign(['sessionprogramme_id']);
                $table->dropColumn('sessionProgramme_id');
            }
        });
    }
};
