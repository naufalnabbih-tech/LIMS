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
            $table->datetime('reviewed_at')->nullable()->after('analysis_completed_at');
            $table->datetime('approved_at')->nullable()->after('reviewed_at');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_at');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_material_samples', function (Blueprint $table) {
            $table->dropColumn(['reviewed_at', 'approved_at', 'reviewed_by', 'approved_by']);
        });
    }
};
