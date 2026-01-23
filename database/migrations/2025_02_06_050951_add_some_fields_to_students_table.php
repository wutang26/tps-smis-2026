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
            $table->boolean('profile_complete')->default(false); // Profile completeness status
            $table->string('photo')->nullable();
            $table->string('status')->default('pending'); // Status: pending, approved, rejected
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('reject_reason')->nullable(); // Reason for rejecting
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->boolean('transcript_printed')->default(false); // Transcript printing status
            $table->boolean('certificate_printed')->default(false); // Certificate printing status
            $table->unsignedBigInteger('printed_by')->nullable();
            $table->text('reprint_reason')->nullable(); // Reason for reprinting
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('profile_complete');
            $table->dropColumn('status');
            $table->dropColumn('approved_at');
            $table->dropColumn('rejected_at');
            $table->dropColumn('reject_reason');
            $table->dropColumn('approved_by');
            $table->dropColumn('rejected_by');
            $table->dropColumn('transcript_printed');
            $table->dropColumn('certificate_printed');
            $table->dropColumn('printed_by');
            $table->dropColumn('reprint_reason');
        });
    }
};
