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
        Schema::create('raw_material_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('raw_mat_categories')->onDelete('cascade');
            $table->foreignId('raw_mat_id')->constrained('raw_mats')->onDelete('cascade');
            $table->string('supplier');
            $table->string('batch_lot');
            $table->string('vehicle_container_number');
            $table->boolean('has_coa')->default(false);
            $table->string('coa_file_path')->nullable();
            $table->datetime('submission_time');
            $table->datetime('entry_time')->useCurrent();
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_samples');
    }
};
