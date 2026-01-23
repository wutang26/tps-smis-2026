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
        Schema::create('borrowed_weapons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weapon_borrower_id')->nullable()->constrained('weapon_borrowers'); // WeaponBorrower
            $table->foreignId('weapon_id')->nullable()->constrained('Weapons'); // Weapons  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowed_weapons');
    }
};
