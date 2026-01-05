<?php

namespace App\Actions\Analysis;

use App\DTOs\ValidationResult;
use App\Services\SpecificationEvaluationService;

class ValidateAnalysisResultsAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(private SpecificationEvaluationService $evaluationService)
    {
    }

    public function execute(array $analysisResults, callable $evaluateCallback): ValidationResult
    {
        // Check for pending specs
        $pendingSpecs = $this->findPendingSpecs($analysisResults);
        if (!empty($pendingSpecs)) {
            return ValidationResult::failed(
                'Please complete all analysis readings before saving. Pending parameters: ' . implode(', ', $pendingSpecs)
            );
        }

        // Validate and count pass/fail
        $stats = $this->evaluateSpecs($analysisResults, $evaluateCallback);

        if (!$stats['hasResults']) {
            return ValidationResult::failed('Please enter at least one analysis reading.');
        }

        return ValidationResult::success($stats['passedSpecs'], $stats['failedSpecs']);
    }

    private function findPendingSpecs(array $analysisResults): array
    {
        return array_filter(
            array_map(
                fn($result) => $result['average_value'] === null ? $result['spec_name'] : null,
                $analysisResults
            )
        );
    }

    private function evaluateSpecs(array $analysisResults, callable $evaluateCallback): array
    {
        $passedSpecs = 0;
        $failedSpecs = 0;
        $hasResults = false;

        foreach ($analysisResults as $parameter => $result) {
            if ($this->hasReadings($result['readings'])) {
                $hasResults = true;
                $testValue = $result['average_value'];

                if (isset($result['spec_id'], $result['target_value'], $result['operator']) && $testValue !== null) {
                    $evaluateCallback($result, $testValue) ? $passedSpecs++ : $failedSpecs++;
                }
            }
        }

        return compact('hasResults', 'passedSpecs', 'failedSpecs');
    }

    private function hasReadings(array $readings): bool
    {
        return !empty(array_filter($readings, fn($r) => !empty($r['value'])));
    }
}
