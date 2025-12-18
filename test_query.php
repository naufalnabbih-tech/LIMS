<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$results = DB::table('test_results')->where('sample_id', 2)->get();

echo "Test Results for Sample 2:\n";
echo "==========================\n";

foreach($results as $r) {
    echo "Parameter: {$r->parameter_name}\n";
    echo "  Operator: {$r->spec_operator}\n";
    echo "  Min: " . ($r->spec_min_value ?? 'NULL') . "\n";
    echo "  Max: " . ($r->spec_max_value ?? 'NULL') . "\n";
    echo "  Min !== null? " . ($r->spec_min_value !== null ? 'YES' : 'NO') . "\n";
    echo "  Max !== null? " . ($r->spec_max_value !== null ? 'YES' : 'NO') . "\n";
    echo "  Both not null? " . (($r->spec_min_value !== null && $r->spec_max_value !== null) ? 'YES' : 'NO') . "\n";
    echo "\n";
}
