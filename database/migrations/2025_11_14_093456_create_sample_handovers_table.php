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
        Schema::create('sample_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained('samples')->onUpdate('cascade')->onDelete('cascade');

            $table->foreignId('from_analyst_id')->constrained('users')->onDelete('cascade');

            $table->foreignId('to_analyst_id')->nullable()->constrained('users')->onDelete('cascade');

            $table->enum('new_analysis_method', ['individual', 'joint'])->nullable();
            $table->foreignId('new_secondary_analyst_id')->nullable()->constrained('users')->onDelete('set null');


            // Handover details
            $table->text('notes')->nullable();
            $table->string('reason')->nullable();

            // Timestamps
            $table->timestamp('submitted_at');
            $table->timestamp('taken_at')->nullable();
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('taken_by')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('status', ['pending', 'accepted', 'cancelled'])->default('pending');

            $table->timestamps();

            $table->index('sample_id');
            $table->index(['status', 'submitted_at']);
            $table->index('from_analyst_id');
            $table->index('to_analyst_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_handovers');
    }
};
