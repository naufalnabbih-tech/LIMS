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
            // Original fields for history tracking (never change after initial assignment)
            $table->foreignId('original_primary_analyst_id')->nullable()->after('handover_notes')->constrained('users')->onDelete('set null');
            $table->foreignId('original_secondary_analyst_id')->nullable()->after('original_primary_analyst_id')->constrained('users')->onDelete('set null');
            $table->string('original_analysis_method')->nullable()->after('original_secondary_analyst_id');

            // Hand over tracking fields
            $table->foreignId('handover_submitted_by')->nullable()->after('original_analysis_method')->constrained('users')->onDelete('set null');
            $table->timestamp('handover_submitted_at')->nullable()->after('handover_submitted_by');
            $table->foreignId('handover_taken_by')->nullable()->after('handover_submitted_at')->constrained('users')->onDelete('set null');
            $table->timestamp('handover_taken_at')->nullable()->after('handover_taken_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_samples', function (Blueprint $table) {
            $table->dropConstrainedForeignId('handover_taken_by');
            $table->dropColumn('handover_taken_at');
            $table->dropConstrainedForeignId('handover_submitted_by');
            $table->dropColumn('handover_submitted_at');
            $table->dropColumn('original_analysis_method');
            $table->dropConstrainedForeignId('original_secondary_analyst_id');
            $table->dropConstrainedForeignId('original_primary_analyst_id');
        });
    }
};
