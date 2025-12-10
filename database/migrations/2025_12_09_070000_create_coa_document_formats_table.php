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
        Schema::create('coa_document_formats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama format (contoh: Default Format, Chemical Format)');
            $table->string('prefix')->comment('Prefix dokumen (contoh: TI/COA)');
            $table->string('year_month', 20)->comment('Nomor (hanya angka, contoh: 2512, 1234)');
            $table->string('middle_part')->nullable()->comment('Bagian tengah (contoh: MT, CHM)');
            $table->string('suffix')->default('S0')->comment('Suffix (contoh: S0, S1)');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa_document_formats');
    }
};
