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
            if (Schema::hasColumn('coas', 'issued_date')) {
                $table->dropColumn('issued_date');
            }
            if (Schema::hasColumn('coas', 'expiry_date')) {
                $table->dropColumn('expiry_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coas', function (Blueprint $table) {
            $table->datetime('issued_date')->nullable();
            $table->datetime('expiry_date')->nullable();
        });
    }
};
