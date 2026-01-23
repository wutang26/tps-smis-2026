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
        Schema::create('weapon_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();   // e.g. "Rifle", "Pistol", "Machine Gun"
            $table->text('description')->nullable();
            $table->unsignedBigInteger('weapon_category_id')->nullable();
            $table->foreign('weapon_category_id')->references('id')->on('weapon_categories')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weapon_types');
    }
};
