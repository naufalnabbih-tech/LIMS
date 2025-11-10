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
        Schema::table('test_results', function (Blueprint $table) {
            // Snapshot specification values at the time of testing
            // This ensures historical data integrity when specifications change
            $table->string('spec_operator', 20)->nullable()->after('parameter_name');
            $table->float('spec_min_value')->nullable()->after('spec_operator');
            $table->float('spec_max_value')->nullable()->after('spec_min_value');
            $table->string('spec_unit', 50)->nullable()->after('spec_max_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->dropColumn(['spec_operator', 'spec_min_value', 'spec_max_value', 'spec_unit']);
        });
    }
};
