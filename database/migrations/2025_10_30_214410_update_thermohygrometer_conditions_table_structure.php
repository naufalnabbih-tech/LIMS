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

            // Modify existing columns
            $table->decimal('temperature', 5, 2)->nullable()->change();
            $table->decimal('humidity', 5, 2)->nullable()->change();

            // Drop old columns
            $table->dropColumn('recorded_at');
            $table->dropColumn('notes');

            // Add indexes
            // Index name: thermohygrometer_conditions_shift_operator_name_time_date_index
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
            // --- PERBAIKAN: Hapus Index DULUAN sebelum hapus kolom ---

            // Kita perlu menybutkan nama index secara eksplisit atau array kolom yang sama persis
            // agar Laravel bisa generate nama yang cocok.
            $table->dropIndex(['shift', 'operator_name', 'time', 'date']);
            $table->dropIndex(['date']);

            // --- Baru setelah index dihapus, kita hapus kolomnya ---

            // Drop new columns
            $table->dropColumn(['shift', 'operator_name', 'condition', 'description', 'time', 'date']);

            // Add back old columns
            $table->datetime('recorded_at')->after('humidity');
            $table->text('notes')->nullable()->after('recorded_at');

            // Revert temperature and humidity to NOT NULL
            $table->decimal('temperature', 5, 2)->nullable(false)->change();
            $table->decimal('humidity', 5, 2)->nullable(false)->change();
        });
    }
};
