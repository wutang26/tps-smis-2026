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
        Schema::create('company_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->date('date');
            $table->enum('status',['unverified','verified','falsified'])->default('unverified');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('falsified_reason')->nullable();
            $table->timestamps();

            $table->unique(['company_id','date']);

            $table->foreign('company_id')->references('id')->on('companies')->onupdate('update')->ondelete('null');
            $table->foreign('verified_by')->references('id')->on('users')->onupdate('update')->ondelete('null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_attendances');
    }
};
