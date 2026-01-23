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
            $table->integer('ed')->default(0)->after('adm');
            $table->json('ed_student_ids')->after(column: 'adm_student_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendences', function (Blueprint $table) {
            $table->dropColumn('ed');
            $table->dropColumn('ed_students_ids');
        });
    }
};
