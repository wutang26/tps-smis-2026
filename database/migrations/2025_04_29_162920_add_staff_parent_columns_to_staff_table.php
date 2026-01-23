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
        Schema::table('staff', function (Blueprint $table) {
            $table->string('nationality')->default('Tanzanian')->nullable()->after('maritalStatus');
            $table->json('PoB')->nullable()->after('nationality');//place of birth
            $table->string('fatherParticulars')->nullable()->after('PoB');
            $table->string('motherParticulars')->nullable()->after('fatherParticulars');
            $table->json('fatherPoB')->nullable()->after('motherParticulars');
            $table->json('motherPoB')->nullable()->after('fatherPoB');
            $table->json('parentsAddress')->nullable()->after('motherPoB');
            $table->json('PoD')->nullable()->after('parentsAddress');//place of domicile(home district)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('nationality');
            $table->dropColumn('PoB');
            $table->dropColumn('fatherParticulars');
            $table->dropColumn('motherParticulars');
            $table->dropColumn('fatherPoB');
            $table->dropColumn('motherPoB');
            $table->dropColumn('parentsAddress');
            $table->dropColumn('PoD');
        });
    }
};
