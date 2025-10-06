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
            // Update status enum to include new analysis statuses
            $table->enum('status', ['pending', 'in_progress', 'analysis_completed', 'reviewed', 'approved', 'rejected'])->default('pending')->change();
            
            // Add analysis-related fields
            $table->enum('analysis_method', ['individual', 'joint'])->nullable()->after('status');
            $table->foreignId('primary_analyst_id')->nullable()->constrained('users')->onDelete('set null')->after('analysis_method');
            $table->foreignId('secondary_analyst_id')->nullable()->constrained('users')->onDelete('set null')->after('primary_analyst_id');
            $table->datetime('analysis_started_at')->nullable()->after('secondary_analyst_id');
            $table->datetime('analysis_completed_at')->nullable()->after('analysis_started_at');
            $table->text('analysis_notes')->nullable()->after('analysis_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_samples', function (Blueprint $table) {
            // Remove the added analysis fields
            $table->dropColumn([
                'analysis_method',
                'primary_analyst_id',
                'secondary_analyst_id',
                'analysis_started_at',
                'analysis_completed_at',
                'analysis_notes'
            ]);
            
            // Revert status enum to original values
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
