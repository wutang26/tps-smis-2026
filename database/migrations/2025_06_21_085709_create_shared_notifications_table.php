<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shared_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_audience_id')->constrained()->onDelete('cascade');
            $table->foreignId('notification_type_id')->constrained()->onDelete('cascade'); 
            $table->unsignedBigInteger('notification_category_id')->default(1);
            $table->string('title');
            $table->text('body');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_notifications');
    }
};
