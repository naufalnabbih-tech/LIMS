<?php

namespace App\Livewire;

use App\Models\Sample;
use App\Models\Category;
use App\Models\Material;
use App\Models\Reference;
use App\Models\TestResult;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class AnalysisPage extends Component
{
    public $sampleId;
    public $sample;
    public $reference;
    public $analysisResults = [];
    public $notes = '';
    public $isCompleted = false;
    public $activeReadings = []; // Track which readings are active per parameter
    public $isSaving = false; // Track auto-save state
    public $lastSavedAt = null; // Track last save time

    public function mount($sampleId)
    {
        $this->sampleId = $sampleId;
        $this->sample = Sample::with([
            'category',
            'material',
            'reference.specificationsManytoMany',
            'submittedBy',
            'primaryAnalyst',
            'secondaryAnalyst',
            'testResults'
        ])->findOrFail($sampleId);

        $this->reference = $this->sample->reference;
        $this->isCompleted = $this->sample->status === 'analysis_completed';

        // Initialize analysis results based on reference parameters
        $this->initializeAnalysisResults();

        // Load existing analysis results if any test results exist (for both in_progress and completed)
        if ($this->sample->testResults->isNotEmpty()) {
            $this->loadExistingResults();
        }
    }

    public function initializeAnalysisResults()
    {
        if ($this->reference) {
            // Get actual reference specifications
            $specifications = $this->reference->specificationsManytoMany;

            foreach ($specifications as $spec) {
                $specKey = strtolower(str_replace([' ', '-', '(', ')'], '_', $spec->name));

                // Build specification display text
                $specText = '';
                if ($spec->pivot->operator) {
                    if ($spec->pivot->operator === '-' && $spec->pivot->value !== null) {
                        // Range operator: display as "min - max"
                        $specText = $spec->pivot->value . ' - ' . $spec->pivot->max_value;
                    } elseif ($spec->pivot->operator === 'should_be' && $spec->pivot->text_value !== null) {
                        // Should_be operator: display as "= text_value"
                        $specText = '= ' . $spec->pivot->text_value;
                    } elseif ($spec->pivot->value !== null) {
                        // Other operators: display as "operator value"
                        // Replace == with = for display
                        $displayOperator = $spec->pivot->operator === '==' ? '=' : $spec->pivot->operator;
                        $specText = $displayOperator . ' ' . $spec->pivot->value;
                    }
                }

                // Determine target value based on operator
                $targetValue = $spec->pivot->operator === 'should_be'
                    ? $spec->pivot->text_value
                    : $spec->pivot->value;

                $this->analysisResults[$specKey] = [
                    'readings' => [
                        'initial' => ['value' => '', 'timestamp' => null],
                        'middle' => ['value' => '', 'timestamp' => null],
                        'final' => ['value' => '', 'timestamp' => null]
                    ],
                    'reading_config' => ['initial', 'middle', 'final'],
                    'average_value' => null,
                    'final_value' => null,
                    'spec' => $specText ?: 'As per reference',
                    'spec_name' => $spec->name,
                    'spec_id' => $spec->id,
                    'target_value' => $targetValue,
                    'operator' => $spec->pivot->operator,
                    'max_value' => $spec->pivot->max_value,
                    'text_value' => $spec->pivot->text_value ?? null,
                    'unit' => $spec->pivot->unit ?? ''
                ];

                // Initialize with only initial reading active
                $this->activeReadings[$specKey] = ['initial'];
            }
        }
    }

    private function loadExistingResults()
    {
        // Load from test_results table instead of JSON
        $testResults = $this->sample->testResults;

        foreach ($testResults as $testResult) {
            $specKey = strtolower(str_replace([' ', '-', '(', ')'], '_', $testResult->parameter_name));

            if (isset($this->analysisResults[$specKey])) {
                // Map reading number to reading type
                $readingTypes = ['initial', 'middle', 'final'];
                $readingType = $readingTypes[$testResult->reading_number - 1] ?? 'initial';

                // Load value from correct column based on operator
                if ($testResult->spec_operator === 'should_be') {
                    // For should_be, use test_value_text (no formatting needed)
                    $value = $testResult->test_value_text;
                } else {
                    // For numeric operators, use test_value and format
                    $value = is_numeric($testResult->test_value)
                        ? rtrim(rtrim(number_format($testResult->test_value, 4, '.', ''), '0'), '.')
                        : $testResult->test_value;
                }

                $this->analysisResults[$specKey]['readings'][$readingType]['value'] = $value;
                $this->analysisResults[$specKey]['readings'][$readingType]['timestamp'] = $testResult->tested_at;

                // Add to active readings if not already there
                if (!in_array($readingType, $this->activeReadings[$specKey])) {
                    $this->activeReadings[$specKey][] = $readingType;
                }
            }
        }

        // Recalculate all parameter values based on loaded data
        foreach ($this->analysisResults as $parameter => $result) {
            $this->calculateParameterValues($parameter);
        }

        // Load analysis notes from first test result (not sample notes)
        // Since analysis notes are saved to all test results, we can load from any one
        $firstTestResult = $testResults->first();
        $this->notes = $firstTestResult ? $firstTestResult->notes : '';
    }


    // Livewire hook: auto-save when analysis value updated via wire:model
    public function updated($propertyName)
    {
        // Check if updated property is an analysis reading value
        if (str_starts_with($propertyName, 'analysisResults.')) {
            // Extract parameter and reading type
            // Format: analysisResults.{parameter}.readings.{readingType}.value
            preg_match('/analysisResults\.([^.]+)\.readings\.([^.]+)\.value/', $propertyName, $matches);

            if (count($matches) === 3) {
                $parameter = $matches[1];
                $readingType = $matches[2];

                // Update timestamp
                $this->analysisResults[$parameter]['readings'][$readingType]['timestamp'] = now();

                // Calculate average and final values
                $this->calculateParameterValues($parameter);

                // Auto-save
                $this->autoSave();
            }
        }
    }

    public function updateAnalysisReading($parameter, $readingType, $value)
    {
        if (isset($this->analysisResults[$parameter]) && isset($this->analysisResults[$parameter]['readings'][$readingType])) {
            $this->analysisResults[$parameter]['readings'][$readingType]['value'] = $value;
            $this->analysisResults[$parameter]['readings'][$readingType]['timestamp'] = now();

            // Calculate average and final values
            $this->calculateParameterValues($parameter);

            // Auto-save after value update
            $this->autoSave();
        }
    }

    public function autoSave()
    {
        $this->isSaving = true;

        try {
            // Clear existing test results for this sample
            $this->sample->testResults()->delete();

            // Save individual test results to database
            foreach ($this->analysisResults as $parameter => $result) {
                foreach ($result['readings'] as $readingType => $reading) {
                    // Only save if there's a value
                    if (!empty($reading['value'])) {
                        // Map reading type to reading number
                        $readingNumbers = ['initial' => 1, 'middle' => 2, 'final' => 3];
                        $readingNumber = $readingNumbers[$readingType] ?? 1;

                        // Evaluate if this specific reading passes
                        $passes = false;
                        if (isset($result['spec_id']) && $result['target_value'] !== null && $result['operator']) {
                            // Handle should_be operator for text values
                            if ($result['operator'] === 'should_be') {
                                $passes = $this->evaluateSpecification(
                                    $reading['value'],
                                    $result['target_value'],
                                    $result['operator'],
                                    null
                                );
                            } else {
                                // For numeric operators
                                $maxValue = isset($result['max_value']) ? floatval($result['max_value']) : null;
                                $passes = $this->evaluateSpecification(
                                    floatval($reading['value']),
                                    floatval($result['target_value']),
                                    $result['operator'],
                                    $maxValue
                                );
                            }
                        }

                        // Build base test data
                        $testData = [
                            'sample_id' => $this->sample->id,
                            'specification_id' => $result['spec_id'],
                            'parameter_name' => $result['spec_name'],
                            'reading_number' => $readingNumber,
                            'tested_at' => $reading['timestamp'] ?? Carbon::now('Asia/Jakarta'),
                            'tested_by' => auth()->id(),
                            'notes' => $this->notes,
                            'status' => $passes ? 'pass' : 'fail',
                            'spec_operator' => $result['operator'] ?? null,
                            'spec_unit' => $result['unit'] ?? null
                        ];

                        // Handle data storage based on operator type
                        if ($result['operator'] === 'should_be') {
                            // For text-based should_be: store text in test_value_text, spec values as NULL
                            $testData['test_value_text'] = $reading['value'];
                            $testData['test_value'] = null;
                            $testData['spec_min_value'] = null; // Can't store text in FLOAT column
                            $testData['spec_max_value'] = null;
                        } else {
                            // For numeric operators: store numbers in test_value, spec values as floats
                            $testData['test_value'] = $reading['value'];
                            $testData['test_value_text'] = null;
                            $testData['spec_min_value'] = $result['target_value'] ?? null;
                            $testData['spec_max_value'] = $result['max_value'] ?? null;
                        }

                        TestResult::create($testData);
                    }
                }
            }

            $this->lastSavedAt = now()->format('H:i:s');
        } catch (\Exception $e) {
            \Log::error('Auto-save error: ' . $e->getMessage());
        } finally {
            $this->isSaving = false;
        }
    }

    public function calculateParameterValues($parameter)
    {
        $readings = $this->analysisResults[$parameter]['readings'];
        $operator = $this->analysisResults[$parameter]['operator'] ?? null;

        // For "should_be" operator, handle as text values
        if ($operator === 'should_be') {
            $textValues = [];
            foreach ($readings as $reading) {
                if (!empty($reading['value'])) {
                    $textValues[] = $reading['value'];
                }
            }

            if (!empty($textValues)) {
                // For text values, just use the last reading as final value
                $this->analysisResults[$parameter]['final_value'] = end($textValues);
                $this->analysisResults[$parameter]['average_value'] = end($textValues); // Use final as average for display
            } else {
                $this->analysisResults[$parameter]['average_value'] = null;
                $this->analysisResults[$parameter]['final_value'] = null;
            }
            return;
        }

        // For numeric operators, calculate as before
        $values = [];
        foreach ($readings as $reading) {
            if (!empty($reading['value']) && is_numeric($reading['value'])) {
                $values[] = floatval($reading['value']);
            }
        }

        if (!empty($values)) {
            $this->analysisResults[$parameter]['average_value'] = array_sum($values) / count($values);
            $this->analysisResults[$parameter]['final_value'] = end($values);

            // Add reading variance validation
            if (count($values) > 1) {
                $variance = $this->calculateVariance($values);
                $this->analysisResults[$parameter]['variance'] = $variance;
                $this->analysisResults[$parameter]['high_variance'] = $variance > ($this->analysisResults[$parameter]['average_value'] * 0.05); // 5% threshold
            }
        } else {
            $this->analysisResults[$parameter]['average_value'] = null;
            $this->analysisResults[$parameter]['final_value'] = null;
            $this->analysisResults[$parameter]['variance'] = null;
            $this->analysisResults[$parameter]['high_variance'] = false;
        }
    }

    private function calculateVariance($values)
    {
        $mean = array_sum($values) / count($values);
        $variance = 0;

        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }

        return sqrt($variance / count($values)); // Standard deviation
    }

    public function addReading($parameter)
    {
        if (!isset($this->activeReadings[$parameter])) {
            return;
        }

        $currentActive = $this->activeReadings[$parameter];
        $readingOrder = ['initial', 'middle', 'final'];

        // Find next reading to add
        foreach ($readingOrder as $reading) {
            if (!in_array($reading, $currentActive)) {
                $this->activeReadings[$parameter][] = $reading;
                break;
            }
        }
    }

    public function removeReading($parameter, $readingType)
    {
        if (!isset($this->activeReadings[$parameter]) || $readingType === 'initial') {
            return; // Can't remove initial reading
        }

        // Remove from active readings
        $this->activeReadings[$parameter] = array_values(array_filter(
            $this->activeReadings[$parameter],
            fn($reading) => $reading !== $readingType
        ));

        // Clear the reading value
        if (isset($this->analysisResults[$parameter]['readings'][$readingType])) {
            $this->analysisResults[$parameter]['readings'][$readingType]['value'] = '';
            $this->analysisResults[$parameter]['readings'][$readingType]['timestamp'] = null;
        }

        // Recalculate values
        $this->calculateParameterValues($parameter);
    }

    public function canAddReading($parameter)
    {
        if (!isset($this->activeReadings[$parameter])) {
            return false;
        }

        return count($this->activeReadings[$parameter]) < 3;
    }

    public function saveResults()
    {
        // First validate that ALL specifications have been filled (no pending status)
        $pendingSpecs = [];
        foreach ($this->analysisResults as $parameter => $result) {
            // Recalculate to ensure average_value is up to date
            $this->calculateParameterValues($parameter);

            if ($result['average_value'] === null) {
                $pendingSpecs[] = $result['spec_name'];
            }
        }

        if (!empty($pendingSpecs)) {
            $message = 'Please complete all analysis readings before saving. Pending parameters: ' . implode(', ', $pendingSpecs);
            $this->dispatch('save-error', $message);
            session()->flash('error', $message);
            return;
        }

        // Validate that some results have been entered and evaluate specifications
        $hasResults = false;
        $passedSpecs = 0;
        $failedSpecs = 0;
        $totalTestResults = 0;

        foreach ($this->analysisResults as $parameter => $result) {
            // Check if any reading has been entered
            $hasReadings = false;
            foreach ($result['readings'] as $reading) {
                if (!empty($reading['value'])) {
                    $hasReadings = true;
                    break;
                }
            }

            if ($hasReadings) {
                $hasResults = true;

                // Use average value for specification evaluation
                $testValue = $result['average_value'];

                // Check if result meets specification
                if (isset($result['spec_id']) && $result['target_value'] !== null && $result['operator'] && $testValue !== null) {
                    $operator = $result['operator'];

                    // For should_be operator, use text comparison
                    if ($operator === 'should_be') {
                        $passes = $this->evaluateSpecification($testValue, $result['target_value'], $operator, null);
                    } else {
                        // For numeric operators
                        $targetValue = floatval($result['target_value']);
                        $maxValue = isset($result['max_value']) ? floatval($result['max_value']) : null;
                        $passes = $this->evaluateSpecification($testValue, $targetValue, $operator, $maxValue);
                    }

                    if ($passes) {
                        $passedSpecs++;
                    } else {
                        $failedSpecs++;
                    }
                }
            }
        }

        if (!$hasResults) {
            $this->dispatch('save-error', 'Please enter at least one analysis reading.');
            session()->flash('error', 'Please enter at least one analysis reading.');
            return;
        }

        // Clear existing test results for this sample to replace with new data
        $this->sample->testResults()->delete();

        // Save individual test results to database
        foreach ($this->analysisResults as $parameter => $result) {
            if ($result['average_value'] !== null) {
                foreach ($result['readings'] as $readingType => $reading) {
                    // Only save if there's a value (numeric OR text)
                    if (!empty($reading['value'])) {
                        // Map reading type to reading number
                        $readingNumbers = ['initial' => 1, 'middle' => 2, 'final' => 3];
                        $readingNumber = $readingNumbers[$readingType] ?? 1;

                        // Evaluate if this specific reading passes
                        $passes = false;
                        if (isset($result['spec_id']) && $result['target_value'] !== null && $result['operator']) {
                            // Handle should_be operator for text values
                            if ($result['operator'] === 'should_be') {
                                $passes = $this->evaluateSpecification(
                                    $reading['value'],
                                    $result['target_value'],
                                    $result['operator'],
                                    null
                                );
                            } else {
                                // For numeric operators
                                $maxValue = isset($result['max_value']) ? floatval($result['max_value']) : null;
                                $passes = $this->evaluateSpecification(
                                    floatval($reading['value']),
                                    floatval($result['target_value']),
                                    $result['operator'],
                                    $maxValue
                                );
                            }
                        }

                        // Build base test data
                        $testData = [
                            'sample_id' => $this->sample->id,
                            'specification_id' => $result['spec_id'],
                            'parameter_name' => $result['spec_name'],
                            'reading_number' => $readingNumber,
                            'tested_at' => $reading['timestamp'] ?? Carbon::now('Asia/Jakarta'),
                            'tested_by' => auth()->id(),
                            'notes' => $this->notes,
                            'status' => $passes ? 'pass' : 'fail',
                            'spec_operator' => $result['operator'] ?? null,
                            'spec_unit' => $result['unit'] ?? null
                        ];

                        // Handle data storage based on operator type
                        if ($result['operator'] === 'should_be') {
                            // For text-based should_be: store text in test_value_text, spec values as NULL
                            $testData['test_value_text'] = $reading['value'];
                            $testData['test_value'] = null;
                            $testData['spec_min_value'] = null;
                            $testData['spec_max_value'] = null;
                        } else {
                            // For numeric operators: store numbers in test_value, spec values as floats
                            $testData['test_value'] = floatval($reading['value']);
                            $testData['test_value_text'] = null;
                            $testData['spec_min_value'] = $result['target_value'] ?? null;
                            $testData['spec_max_value'] = $result['max_value'] ?? null;
                        }

                        TestResult::create($testData);

                        $totalTestResults++;
                    }
                }
            }
        }

        // Check if sample has pending handover - cannot complete if handover is pending
        if ($this->sample->hasActiveHandover()) {
            $this->dispatch('save-error', 'Cannot complete analysis. This sample has a pending handover. Please wait for the handover to be accepted or cancelled first.');
            session()->flash('error', 'Cannot complete analysis. This sample has a pending handover.');
            return;
        }

        // Save analysis completion status
        $status = 'analysis_completed';
        $message = "Analysis results saved successfully! $totalTestResults test readings recorded.";

        if ($failedSpecs > 0) {
            $message .= " Note: $failedSpecs parameter(s) failed, $passedSpecs passed.";
        } elseif ($passedSpecs > 0) {
            $message .= " All $passedSpecs parameters passed.";
        }

        // Get analysis_completed status ID
        $analysisCompletedStatus = \App\Models\Status::where('name', 'analysis_completed')->first();

        $this->sample->update([
            'status_id' => $analysisCompletedStatus ? $analysisCompletedStatus->id : null,
            'status' => 'analysis_completed', // Keep for backward compatibility
            'analysis_completed_at' => Carbon::now('Asia/Jakarta'),
        ]);

        $this->isCompleted = true;
        $this->dispatch('save-success', $message);
        session()->flash('message', $message);
    }

    private function evaluateSpecification($testValue, $targetValue, $operator, $maxValue = null)
    {
        switch ($operator) {
            case 'should_be':
                // Case-insensitive text comparison
                return strcasecmp(trim($testValue), trim($targetValue)) === 0;
            case '>=':
                return $testValue >= $targetValue;
            case '>':
                return $testValue > $targetValue;
            case '<=':
                return $testValue <= $targetValue;
            case '<':
                return $testValue < $targetValue;
            case '=':
            case '==':
                return abs($testValue - $targetValue) < 0.001; // Small tolerance for float comparison
            case '-':
                // Range operator: check if test value is within min-max range
                if ($maxValue !== null) {
                    return $testValue >= $targetValue && $testValue <= $maxValue;
                }
                return true; // If no max value, assume pass
            default:
                return true; // Unknown operator, assume pass
        }
    }

    public function completeAnalysis()
    {
        // Check if sample has pending handover - cannot complete if handover is pending
        if ($this->sample->hasActiveHandover()) {
            session()->flash('error', 'Cannot complete analysis. This sample has a pending handover. Please wait for the handover to be accepted or cancelled first.');
            return;
        }

        // Get analysis_completed status ID
        $analysisCompletedStatus = \App\Models\Status::where('name', 'analysis_completed')->first();

        $this->sample->update([
            'status_id' => $analysisCompletedStatus ? $analysisCompletedStatus->id : null,
            'status' => 'analysis_completed', // Keep for backward compatibility
            'analysis_completed_at' => Carbon::now('Asia/Jakarta')
        ]);

        session()->flash('message', 'Analysis marked as completed!');
        return redirect()->route('sample-rawmat-submissions');
    }

    public function backToSamples()
    {
        // Redirect based on sample type
        $sampleType = $this->sample->sample_type;

        switch ($sampleType) {
            case 'raw_material':
                return redirect()->route('sample-rawmat-submissions');
            case 'solder':
                return redirect()->route('sample-solder-submissions');
            case 'chemical':
                return redirect()->route('sample-chemical-submissions');
            default:
                return redirect()->route('sample-rawmat-submissions');
        }
    }

    public function render()
    {
        return view('livewire.analysis.analysis-page')
            ->layout('layouts.app')
            ->title('Analysis - Sample #' . $this->sample->id);
    }
}
