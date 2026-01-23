<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('semester_exams', function (Blueprint $table) {
            $table->string('exam_title')->nullable()->after('semester_id');
        });
    }

    public function down(): void
    {
        Schema::table('semester_exams', function (Blueprint $table) {
            $table->dropColumn('exam_title');
        });
    }
};
