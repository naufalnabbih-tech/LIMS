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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('sample_type');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->foreignId('reference_id')->nullable()->constrained('references')->onDelete('set null');

            // Sample details
            $table->string('supplier');
            $table->string('batch_lot');
            $table->string('vehicle_container_number');
            $table->boolean('has_coa')->default(false);
            $table->string('coa_file_path')->nullable();

            // Submission
            $table->datetime('submission_time');
            $table->datetime('entry_time')->useCurrent();
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');

            // Status
            $table->foreignId('status_id')->constrained('statuses')->onDelete('restrict');

            // Analysis workflow
            $table->enum('analysis_method', ['individual', 'joint'])->nullable();
            $table->foreignId('primary_analyst_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('secondary_analyst_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('analysis_started_at')->nullable();
            $table->datetime('analysis_completed_at')->nullable();

            // Review & Approval
            $table->datetime('reviewed_at')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');

            // Rejection
            $table->datetime('rejected_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');

            // Results & Notes
            $table->json('analysis_results')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();


            $table->index('sample_type');
            $table->index('status_id');
            $table->index(['sample_type', 'status_id']);
            $table->index('created_at');
            $table->index(['sample_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
