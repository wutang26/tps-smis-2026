<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weapon_handovers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('weapon_id')->nullable()->constrained('weapons'); // Weapon
            $table->foreignId('staff_id')->nullable()->constrained('staff'); // Staff taking the weapon
            
            $table->foreignId('handover_armorer_id')->nullable()->constrained('users'); // Armorer handing out
            $table->foreignId('return_armorer_id')->nullable()->constrained('users'); // Armorer receiving back
            
            $table->timestamp('handover_at')->nullable();
            $table->timestamp('expected_return_at')->nullable();

            $table->timestamp('returned_at')->nullable();
            
            $table->string('purpose'); // e.g., Beats, Escort weapons
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_handovers');
    }
};
