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
        Schema::table('raw_material_samples', function (Blueprint $table) {
            $table->foreignId('reference_id')->nullable()->after('raw_mat_id')->constrained('references')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_samples', function (Blueprint $table) {
            $table->dropForeign(['reference_id']);
            $table->dropColumn('reference_id');
        });
    }
};
