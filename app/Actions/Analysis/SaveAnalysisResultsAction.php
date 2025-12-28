<?php

namespace App\Actions\Analysis;

use App\Models\Sample;
use App\Models\TestResult;
use Carbon\Carbon;

class SaveAnalysisResultsAction
{
    /**
     * Save analysis results to database
     *
     * @param Sample $sample
     * @param array $analysisResults
     * @param string $notes
     * @param callable $evaluateReadingCallback Callback to evaluate if reading passes
     * @return int Total number of test results saved
     */
    public function execute(
        Sample $sample,
        array $analysisResults,
        string $notes,
        callable $evaluateReadingCallback
    ): int {
        // Clear existing test results for this sample
        $sample->testResults()->delete();

        $totalSaved = 0;

        // Save individual test results to database
        foreach ($analysisResults as $parameter => $result) {
            foreach ($result['readings'] as $readingType => $reading) {
                // Only save if there's a value
                if (!empty($reading['value'])) {
                    $testData = $this->buildTestData(
                        $sample,
                        $result,
                        $reading,
                        $readingType,
                        $notes,
                        $evaluateReadingCallback
                    );

                    TestResult::create($testData);
                    $totalSaved++;
                }
            }
        }

        return $totalSaved;
    }


    /**
     * Build test data array for creating TestResult
     */
    private function buildTestData(
        Sample $sample,
        array $result,
        array $reading,
        string $readingType,
        string $notes,
        callable $evaluateReadingCallback
    ): array {
        // Map reading type to reading number
        $readingNumbers = ['initial' => 1, 'middle' => 2, 'final' => 3];
        $readingNumber = $readingNumbers[$readingType] ?? 1;

        // Evaluate if this specific reading passes using callback
        $passes = $evaluateReadingCallback($result, $reading['value']);

        // Build base test data
        $baseData = [
            'sample_id' => $sample->id,
            'specification_id' => $result['spec_id'],
            'parameter_name' => $result['spec_name'],
            'reading_number' => $readingNumber,
            'tested_at' => $reading['timestamp'] ?? Carbon::now('Asia/Jakarta'),
            'tested_by' => auth()->id(),
            'notes' => $notes,
            'status' => $passes ? 'pass' : 'fail',
            'spec_operator' => $result['operator'] ?? null,
            'spec_unit' => $result['unit'] ?? null,
        ];

        // Merge with operator-specific data
        return array_merge(
            $baseData,
            $this->getOperatorSpecificData($result, $reading['value'])
        );
    }

    /**
     * Get operator-specific data fields
     */
    private function getOperatorSpecificData(array $result, $value): array
    {
        if ($result['operator'] === 'should_be') {
            // For text-based should_be: store text in test_value_text and spec_text_value
            return [
                'test_value_text' => $value,
                'test_value' => null,
                'spec_text_value' => $result['text_value'] ?? null,
                'spec_min_value' => null,
                'spec_max_value' => null,
            ];
        }

        // For numeric operators: store numbers in test_value, spec values as floats
        return [
            'test_value' => $value,
            'test_value_text' => null,
            'spec_text_value' => null,
            'spec_min_value' => $result['target_value'] ?? null,
            'spec_max_value' => $result['max_value'] ?? null,
        ];
    }

}
