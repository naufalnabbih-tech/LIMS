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
        Schema::table('reference_specification', function (Blueprint $table) {
            // Add new spec_value column for text-based specifications (should_be operator)
            $table->string('spec_value')->nullable()->after('max_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reference_specification', function (Blueprint $table) {
            $table->dropColumn('spec_value');
        });
    }
};
