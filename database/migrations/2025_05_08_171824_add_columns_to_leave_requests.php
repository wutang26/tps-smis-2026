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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->date('return_date')->nullable();
            $table->integer('previous_beat_status')->nullable();
            $table->integer('current_beat_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('return_date');
            $table->dropColumn('previous_beat_status');
            $table->dropColumn('current_beat_status');
        });
    }
};
