<?php
/**
 * Cleanup script for old range operator data
 * Run with: php cleanup_range_data.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Cleaning up old range operator data...\n";

// Find all reference_specification with operator '-'
$ranges = DB::table('reference_specification')
    ->where('operator', '-')
    ->get();

echo "Found {$ranges->count()} records with range operator\n";

foreach ($ranges as $range) {
    echo "\nProcessing ID: {$range->id}\n";
    echo "  Current value: {$range->value}\n";
    echo "  Current max_value: {$range->max_value}\n";

    // Check if value is JSON (old format)
    $decoded = json_decode($range->value, true);

    if (is_array($decoded) && !empty($decoded)) {
        // Old format: JSON array [{"min":"1","max":"2"}]
        echo "  Format: OLD (JSON)\n";

        // Get first range from array
        $firstRange = $decoded[0];
        $min = $firstRange['min'] ?? null;
        $max = $firstRange['max'] ?? null;

        echo "  Converting to: min={$min}, max={$max}\n";

        // Update to new format
        DB::table('reference_specification')
            ->where('id', $range->id)
            ->update([
                'value' => $min,
                'max_value' => $max
            ]);

        echo "  ✓ Converted\n";
    } else {
        // New format or already migrated
        echo "  Format: NEW (already correct)\n";
    }
}

echo "\n✓ Cleanup complete!\n";
