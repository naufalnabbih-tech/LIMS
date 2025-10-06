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
        Schema::create('solder_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('solder_categories')->onDelete('cascade');
            $table->foreignId('solder_id')->constrained('solders')->onDelete('cascade');
            $table->foreignId('reference_id')->nullable()->constrained('solder_references')->onDelete('set null');
            $table->string('supplier');
            $table->string('batch_lot');
            $table->string('vehicle_container_number');
            $table->boolean('has_coa')->default(false);
            $table->string('coa_file_path')->nullable();
            $table->datetime('submission_time');
            $table->datetime('entry_time')->useCurrent();
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'submitted', 'in_progress', 'analysis_completed', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('analysis_method')->nullable();
            $table->foreignId('primary_analyst_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('secondary_analyst_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('analysis_started_at')->nullable();
            $table->datetime('analysis_completed_at')->nullable();
            $table->datetime('reviewed_at')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('analysis_results')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solder_samples');
    }
};