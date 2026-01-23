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
        Schema::create('staff_task', function (Blueprint $table) {
            $table->id();
            // Foreign keys
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained('regions');
            $table->foreignId('district_id')->constrained('districts')->nullable();
            // Assignment metadata
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            // 🛠️ Performance: Indexes
            $table->index('staff_id');
            $table->index('task_id');
            $table->index('is_active');
            $table->index(['region_id', 'district_id'], 'staff_task_region_district_index');

            $table->unique(['staff_id', 'task_id', 'region_id', 'district_id'], 'unique_staff_task_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_task');
    }
};
    