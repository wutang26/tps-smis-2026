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
            $table->integer('lockUp')->after(column: 'off')->nullable();
            $table->json('lockUp_students_ids')->after(column: 'lockUp')->nullable();
            $table->integer('kazini')->after(column: 'off')->nullable();
            $table->integer('sick')->after(column: 'kazini')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendences', function (Blueprint $table) {
            $table->dropColumn('lockUp');
            $table->dropColumn('lockUp_students_ids');
            $table->dropColumn('kazini');
            $table->dropColumn('sick');
        });
    }
};
