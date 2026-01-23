<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weapon_models', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Example: AK-47, Glock 17, M4A1
            $table->string('description');
            $table->unsignedBigInteger('weapon_type_id')->nullable();
            $table->foreign('weapon_type_id')->references('id')->on('weapon_types')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weapon_models');
    }
};
