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
        Schema::create('termination_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Indexes for better performance on user tracking fields
            $table->index('created_by');
            $table->index('updated_by');

            // Foreign key constraints (assuming you have a users table)
            $table->foreign('created_by')
                ->references('id')->on('users')->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('termination_reasons');
    }
};

