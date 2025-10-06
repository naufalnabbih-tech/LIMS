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
        Schema::create('analysis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_rawmat_id')->constrained('sample_rawmats')->onDelete('cascade');
            $table->foreignId('specification_id')->constrained('specifications')->onDelete('cascade');
            $table->string('result_value')->nullable();
            $table->enum('status', ['pending', 'completed', 'passed', 'failed'])->default('pending');
            $table->string('tested_by')->nullable();
            $table->timestamp('tested_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['sample_rawmat_id', 'specification_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis_results');
    }
};
