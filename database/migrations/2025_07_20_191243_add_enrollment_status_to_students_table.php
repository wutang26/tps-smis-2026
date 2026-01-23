<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('enrollment_status')->default(1)->after('study_level_id');
            $table->foreignId('dismissed_by')->nullable()->constrained('users')->after('enrollment_status');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['enrollment_status', 'dismissed_by']);
        });
    }
};

