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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Who performed the action
            $table->unsignedBigInteger('user_id')->nullable();

            // What action was performed
            $table->string('action'); // e.g., delete_user, update_profile, login

            // What was affected
            $table->string('target_type')->nullable(); // e.g., User, CourseWork
            $table->unsignedBigInteger('target_id')->nullable();

            // Optional JSON snapshots
            $table->json('metadata')->nullable();     // extra info like title, email
            $table->json('old_values')->nullable();   // before change
            $table->json('new_values')->nullable();   // after change

            // Traceability
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index(['target_type', 'target_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
