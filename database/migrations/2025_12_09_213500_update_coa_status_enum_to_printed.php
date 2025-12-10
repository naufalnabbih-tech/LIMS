<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Normalize existing 'released' to 'printed' before altering enum
        DB::table('coas')->where('status', 'released')->update(['status' => 'printed']);

        // Change enum definition to include 'printed' and remove 'released'
        DB::statement("ALTER TABLE coas MODIFY status ENUM('draft','pending_review','approved','printed','archived') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Revert enum and map printed back to released
        DB::table('coas')->where('status', 'printed')->update(['status' => 'released']);

        DB::statement("ALTER TABLE coas MODIFY status ENUM('draft','pending_review','approved','released','archived') NOT NULL DEFAULT 'draft'");
    }
};
