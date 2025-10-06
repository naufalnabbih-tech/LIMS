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
            // Add status_id column
            $table->foreignId('status_id')->nullable()->after('status')->constrained('statuses')->onDelete('restrict');

            // We'll keep the old status column temporarily for data migration
            // It will be dropped in a separate migration after data is migrated
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_samples', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
