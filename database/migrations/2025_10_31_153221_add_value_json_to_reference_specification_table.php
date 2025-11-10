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
            // Add new value_json column for range operator data
            $table->text('value_json')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reference_specification', function (Blueprint $table) {
            $table->dropColumn('value_json');
        });
    }
};
