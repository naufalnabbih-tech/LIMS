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
        Schema::table('coas', function (Blueprint $table) {
            // Add format_id reference
            $table->foreignId('format_id')->nullable()->after('document_number')->constrained('coa_document_formats')->onDelete('set null');

            // Remove issued_date and expiry_date
            $table->dropColumn(['issued_date', 'expiry_date']);

            // Add new fields
            $table->string('net_weight')->nullable()->after('sample_type');
            $table->string('po_no')->nullable()->after('net_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coas', function (Blueprint $table) {
            // Remove new fields
            $table->dropForeign(['format_id']);
            $table->dropColumn(['format_id', 'net_weight', 'po_no']);

            // Restore old fields
            $table->dateTime('issued_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
        });
    }
};
