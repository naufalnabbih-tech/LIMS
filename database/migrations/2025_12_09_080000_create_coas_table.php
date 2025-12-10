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
        Schema::create('coas', function (Blueprint $table) {
            $table->id();

            // CoA Document Info
            $table->string('document_number')->index();
            $table->enum('status', ['draft', 'pending_review', 'approved', 'printed', 'archived'])->default('draft')->index();

            // Format Reference
            $table->foreignId('format_id')->nullable()->constrained('coa_document_formats')->onDelete('set null');

            // Sample Reference
            $table->foreignId('sample_id')->constrained('samples')->onDelete('cascade');
            $table->string('sample_type')->index(); // 'chemical', 'solder', 'rawmat'
            $table->string('net_weight')->nullable();
            $table->string('po_no')->nullable();

            // Dates
            $table->dateTime('release_date')->nullable();

            // Approvals
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();

            // File & Data
            $table->string('file_path')->nullable();
            $table->longText('data')->nullable(); // JSON data for CoA content
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['sample_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coas');
    }
};
