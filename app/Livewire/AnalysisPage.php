<?php

namespace App\Livewire;

use App\Models\RawMaterialSample;
use App\Models\RawMatCategory;
use App\Models\RawMat;
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

    public function mount($sampleId)
    {
        $this->sampleId = $sampleId;
        $this->sample = RawMaterialSample::with([
            'category', 
            'rawMaterial', 
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
        
        // Load existing analysis results if completed
        if ($this->isCompleted) {
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
                if ($spec->pivot->operator && $spec->pivot->value !== null) {
                    $specText = $spec->pivot->operator . ' ' . $spec->pivot->value;
                    if ($spec->pivot->max_value) {
                        $specText .= ' - ' . $spec->pivot->max_value;
                    }
                }

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
                    'target_value' => $spec->pivot->value,
                    'operator' => $spec->pivot->operator,
                    'max_value' => $spec->pivot->max_value
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
                
                $this->analysisResults[$specKey]['readings'][$readingType]['value'] = $testResult->test_value;
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
        
        // Load notes
        $this->notes = $this->sample->notes ?? '';
    }
    

    public function updateAnalysisReading($parameter, $readingType, $value)
    {
        if (isset($this->analysisResults[$parameter]) && isset($this->analysisResults[$parameter]['readings'][$readingType])) {
            $this->analysisResults[$parameter]['readings'][$readingType]['value'] = $value;
            $this->analysisResults[$parameter]['readings'][$readingType]['timestamp'] = now();
            
            // Calculate average and final values
            $this->calculateParameterValues($parameter);
        }
    }
    
    public function calculateParameterValues($parameter)
    {
        $readings = $this->analysisResults[$parameter]['readings'];
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
        // Validate that some results have been entered
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
                
                // Recalculate values to ensure they're up to date
                $this->calculateParameterValues($parameter);
                
                // Use average value for specification evaluation
                $testValue = $result['average_value'];
                
                // Check if result meets specification
                if (isset($result['spec_id']) && $result['target_value'] !== null && $result['operator'] && $testValue !== null) {
                    $targetValue = floatval($result['target_value']);
                    $operator = $result['operator'];
                    
                    $passes = $this->evaluateSpecification($testValue, $targetValue, $operator);
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
                    if (!empty($reading['value']) && is_numeric($reading['value'])) {
                        // Map reading type to reading number
                        $readingNumbers = ['initial' => 1, 'middle' => 2, 'final' => 3];
                        $readingNumber = $readingNumbers[$readingType] ?? 1;
                        
                        // Evaluate if this specific reading passes
                        $passes = false;
                        if (isset($result['spec_id']) && $result['target_value'] !== null && $result['operator']) {
                            $passes = $this->evaluateSpecification(
                                floatval($reading['value']), 
                                floatval($result['target_value']), 
                                $result['operator']
                            );
                        }
                        
                        TestResult::create([
                            'sample_id' => $this->sample->id,
                            'specification_id' => $result['spec_id'],
                            'parameter_name' => $result['spec_name'],
                            'test_value' => floatval($reading['value']),
                            'reading_number' => $readingNumber,
                            'tested_at' => $reading['timestamp'] ?? Carbon::now('Asia/Jakarta'),
                            'tested_by' => auth()->id(),
                            'notes' => $this->notes,
                            'status' => $passes ? 'pass' : 'fail'
                        ]);
                        
                        $totalTestResults++;
                    }
                }
            }
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
            'notes' => $this->notes,
        ]);

        $this->isCompleted = true;
        $this->dispatch('save-success', $message);
        session()->flash('message', $message);
    }
    
    private function evaluateSpecification($testValue, $targetValue, $operator)
    {
        switch ($operator) {
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
            default:
                return true; // Unknown operator, assume pass
        }
    }

    public function completeAnalysis()
    {
        // Get analysis_completed status ID
        $analysisCompletedStatus = \App\Models\Status::where('name', 'analysis_completed')->first();

        $this->sample->update([
            'status_id' => $analysisCompletedStatus ? $analysisCompletedStatus->id : null,
            'status' => 'analysis_completed', // Keep for backward compatibility
            'analysis_completed_at' => Carbon::now('Asia/Jakarta')
        ]);

        session()->flash('message', 'Analysis marked as completed!');
        return redirect()->route('sample-submissions');
    }

    public function backToSamples()
    {
        return redirect()->route('sample-submissions');
    }

    public function render()
    {
        return view('livewire.analysis-page')
            ->layout('layouts.app')
            ->title('Analysis - Sample #' . $this->sample->id);
    }
}