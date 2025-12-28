<?php

namespace App\Livewire;


use App\Actions\Analysis\SaveAnalysisResultsAction;
use App\Repositories\SampleRepository;
use App\Repositories\StatusRepository;
use App\Services\AnalysisCalculationService;
use App\Services\SpecificationEvaluationService;
use Livewire\Component;

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

    protected SpecificationEvaluationService $evaluationService;
    protected AnalysisCalculationService $calculationService;
    protected SaveAnalysisResultsAction $saveAnalysisAction;
    protected SampleRepository $sampleRepository;
    protected StatusRepository $statusRepository;

    private function evaluateReading(array $result, $readingValue): bool
    {
        if (!isset($result['spec_id'], $result['target_value'], $result['operator'])) {
            return false;
        }

        return $this->evaluationService->evaluate(
            $readingValue,
            $result['target_value'],
            $result['operator'],
            $result['max_value'] ?? null
        );
    }

    public function mount($sampleId)
    {
        $this->sampleId = $sampleId;
        $this->sample = $this->sampleRepository->findWithCompleteAnalysisData($sampleId);

        if (!$this->sample) {
            abort(404, 'Sample not found');
        }

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
            $this->saveAnalysisAction->execute(
                $this->sample,
                $this->analysisResults,
                $this->notes,
                fn(array $result, $value) => $this->evaluateReading($result, $value)
            );

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

        // Use service to calculate values
        $calculated = $this->calculationService->calculateParameterValues($readings, $operator);

        // Update analysis results with calculated values
        $this->analysisResults[$parameter]['average_value'] = $calculated['average_value'];
        $this->analysisResults[$parameter]['final_value'] = $calculated['final_value'];

        // Set variance values (will be null for text operators)
        $this->analysisResults[$parameter]['variance'] = $calculated['variance'] ?? null;
        $this->analysisResults[$parameter]['high_variance'] = $calculated['high_variance'] ?? false;
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
                    $passes = $this->evaluateReading($result, $testValue);

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

        $totalTestResults = $this->saveAnalysisAction->execute(
            $this->sample,
            $this->analysisResults,
            $this->notes,
            fn($result, $value) => $this->evaluateReading($result, $value)
        );

        // Check if sample has pending handover - cannot complete if handover is pending
        if ($this->sampleRepository->hasActiveHandover($this->sample)) {
            $this->dispatch('save-error', 'Cannot complete analysis. This sample has a pending handover. Please wait for the handover to be accepted or cancelled first.');
            session()->flash('error', 'Cannot complete analysis. This sample has a pending handover.');
            return;
        }


        $message = "Analysis results saved successfully! $totalTestResults test readings recorded.";

        if ($failedSpecs > 0) {
            $message .= " Note: $failedSpecs parameter(s) failed, $passedSpecs passed.";
        } elseif ($passedSpecs > 0) {
            $message .= " All $passedSpecs parameters passed.";
        }

        // Get analysis_completed status ID and update sample
        $statusId = $this->statusRepository->getAnalysisCompletedStatusId();
        $this->sampleRepository->markAsAnalysisCompleted($this->sample, $statusId);

        $this->isCompleted = true;
        $this->dispatch('save-success', $message);
        session()->flash('message', $message);
    }

    public function boot(
        SpecificationEvaluationService $evaluationService,
        AnalysisCalculationService $calculationService,
        SaveAnalysisResultsAction $saveAnalysisAction,
        StatusRepository $statusRepository,
        SampleRepository $sampleRepository
    ) {
        $this->evaluationService = $evaluationService;
        $this->calculationService = $calculationService;
        $this->saveAnalysisAction = $saveAnalysisAction;
        $this->statusRepository = $statusRepository;
        $this->sampleRepository = $sampleRepository;
    }

    public function completeAnalysis()
    {
        // Check if sample has pending handover - cannot complete if handover is pending
        if ($this->sampleRepository->hasActiveHandover($this->sample)) {
            session()->flash('error', 'Cannot complete analysis. This sample has a pending handover. Please wait for the handover to be accepted or cancelled first.');
            return;
        }

        // Mark sample as analysis completed using repository
        $statusId = $this->statusRepository->getAnalysisCompletedStatusId();
        $this->sampleRepository->markAsAnalysisCompleted($this->sample, $statusId);


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
