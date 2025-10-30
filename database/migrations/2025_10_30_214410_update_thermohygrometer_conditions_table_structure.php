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
        Schema::table('thermohygrometer_conditions', function (Blueprint $table) {
            // Add new columns
            $table->string('shift', 50)->after('thermohygrometer_id');
            $table->string('operator_name', 500)->after('shift');
            $table->enum('condition', ['good', 'damaged'])->after('operator_name');
            $table->text('description')->nullable()->after('humidity');
            $table->time('time')->after('description');
            $table->date('date')->after('time');

            // Modify existing columns to be nullable for migration
            $table->decimal('temperature', 5, 2)->nullable()->change();
            $table->decimal('humidity', 5, 2)->nullable()->change();

            // Drop old columns that are no longer used
            $table->dropColumn('recorded_at');
            $table->dropColumn('notes');

            // Add indexes for better query performance
            $table->index(['shift', 'operator_name', 'time', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thermohygrometer_conditions', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['shift', 'operator_name', 'condition', 'description', 'time', 'date']);

            // Add back old columns
            $table->datetime('recorded_at')->after('humidity');
            $table->text('notes')->nullable()->after('recorded_at');

            // Revert temperature and humidity to NOT NULL
            $table->decimal('temperature', 5, 2)->nullable(false)->change();
            $table->decimal('humidity', 5, 2)->nullable(false)->change();

            // Drop new indexes
            $table->dropIndex(['shift', 'operator_name', 'time', 'date']);
            $table->dropIndex(['date']);
        });
    }
};
