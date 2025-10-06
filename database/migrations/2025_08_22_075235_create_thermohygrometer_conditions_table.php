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
        Schema::create('thermohygrometer_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thermohygrometer_id')->constrained()->onDelete('cascade');
            $table->decimal('temperature', 5, 2);
            $table->decimal('humidity', 5, 2);
            $table->datetime('recorded_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('thermohygrometer_id');
            $table->index('recorded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thermohygrometer_conditions');
    }
};
