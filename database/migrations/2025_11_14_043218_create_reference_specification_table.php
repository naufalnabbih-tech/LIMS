<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reference_specification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reference_id')->constrained('references')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('specification_id')->constrained('specifications')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('operator', ['>=', '<=', '==', '-', 'should_be'])->default('==');
            $table->float('value')->nullable();
            $table->float('max_value')->nullable();
            $table->string('text_value')->nullable();
            $table->string('unit')->nullable();
            $table->timestamps();
            // Ensure unique combination of reference and specification
            $table->unique(['reference_id', 'specification_id'], 'ref_spec_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_specification');
    }
};
