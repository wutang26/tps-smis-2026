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
        Schema::create('weapon_borrowers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armorer_id')->nullable()->constrained('users'); // Armorer 
            $table->foreignId('approved_by')->nullable()->constrained('users'); //OC Armoury
            $table->text('name');
            $table->json('received_officer');
            $table->timestamp('returned_at')->nullable();
            $table->date('start_date');
            $table->date('expected_return_date');
            $table->enum('status',['pending','approved','rejected','returned'])->default('pending');         
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapon_borrowers');
    }
};
