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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('raw_material_samples')->onDelete('cascade');
            $table->foreignId('specification_id')->constrained('specifications')->onDelete('cascade');
            $table->string('parameter_name'); // e.g., 'Sn', 'Cu', 'Pb'
            $table->decimal('test_value', 10, 4);
            $table->string('unit', 20)->default('unit');
            $table->integer('reading_number')->default(1); // For multiple readings of same parameter
            $table->timestamp('tested_at');
            $table->foreignId('tested_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->enum('status', ['pass', 'fail', 'pending'])->default('pending');
            $table->timestamps();
            
            // Ensure unique combination of sample, specification, and reading number
            $table->unique(['sample_id', 'specification_id', 'reading_number'], 'test_results_unique');
            
            $table->index(['sample_id', 'parameter_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
