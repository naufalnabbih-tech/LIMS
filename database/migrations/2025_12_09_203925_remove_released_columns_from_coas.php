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
            $table->dropForeign('coas_released_by_foreign');
            $table->dropColumn(['released_by', 'released_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coas', function (Blueprint $table) {
            $table->unsignedBigInteger('released_by')->nullable()->after('approved_at');
            $table->timestamp('released_at')->nullable()->after('released_by');
            $table->foreign('released_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
