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
        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('weaponModel_id')->nullable();
            $table->unsignedBigInteger('weaponOwnershipType_id')->nullable();
            $table->json('owner')->nullable();
            $table->enum('status',['available','taken','borrowed']);
            $table->enum('condition',['good','broken', 'maintained']);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('weaponModel_id')->references('id')->on('weapon_models')->onDelete('set null');
            $table->foreign('weaponOwnershipType_id')->references('id')->on('weapon_ownership_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapons');
    }
};
