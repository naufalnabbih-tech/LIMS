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
        Schema::create('solder_reference_specification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solder_reference_id')->constrained('solder_references')->onDelete('cascade');
            $table->foreignId('specification_id')->constrained('specifications')->onDelete('cascade');
            $table->enum('operator', ['>=', '<=', '==', '-', 'should_be', 'range'])->default('==');
            $table->text('value')->nullable();
            $table->double('max_value')->nullable();
            $table->timestamps();

            $table->unique(['solder_reference_id', 'specification_id'], 'solder_ref_spec_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solder_reference_specification');
    }
};
