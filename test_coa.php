<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->boot();

$coas = \App\Models\CoA::with('sample', 'approver')->limit(5)->get();

echo "Total CoAs: " . count($coas) . "\n";
if ($coas->isNotEmpty()) {
    foreach ($coas as $coa) {
        echo "ID: {$coa->id}, Status: {$coa->status}, DocNum: {$coa->document_number}, Sample: {$coa->sample?->id}\n";
    }
} else {
    echo "No CoAs found. Creating test data...\n";
}
