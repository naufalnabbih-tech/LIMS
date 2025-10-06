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
            $table->foreignId('handover_to_analyst_id')->nullable()->after('rejected_by')->constrained('users')->onDelete('set null');
            $table->text('handover_notes')->nullable()->after('handover_to_analyst_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_samples', function (Blueprint $table) {
            $table->dropConstrainedForeignId('handover_to_analyst_id');
            $table->dropColumn('handover_notes');
        });
    }
};
