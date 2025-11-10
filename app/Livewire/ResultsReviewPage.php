<?php

namespace App\Livewire;

use App\Models\RawMaterialSample;
use App\Models\TestResult;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class ResultsReviewPage extends Component
{
    protected $listeners = [];

    protected function rules()
    {
        return [
            'reviewNotes' => 'required|string|max:1000'
        ];
    }

    // Prevent Livewire from trying to serialize complex model relationships
    public function dehydrate()
    {
        // Clear complex objects before serialization to prevent "Trailing data" errors
        unset($this->sample);
        unset($this->reference);
        unset($this->analysisResults);
        unset($this->statusHistory);
    }

    public function hydrate()
    {
        // Reload sample data after hydration only if needed
        if ($this->sampleId && (!isset($this->sample) || !$this->sample)) {
            $this->loadSampleData();
            $this->loadAnalysisResults();
            $this->buildStatusHistory();
            $this->checkPermissions();
        }
    }

    private function loadSampleData()
    {
        $this->sample = RawMaterialSample::with([
            'category',
            'rawMaterial',
            'reference.specificationsManytoMany',
            'submittedBy',
            'primaryAnalyst',
            'secondaryAnalyst',
            'reviewedBy',
            'approvedBy',
            'testResults.specification',
            'testResults.testedBy',
            'statusRelation'
        ])->findOrFail($this->sampleId);

        $this->reference = $this->sample->reference;
    }
    public $sampleId;
    public $sample;
    public $reference;
    public $analysisResults = [];
    public $statusHistory = [];
    public $reviewNotes = '';
    public $showApprovalForm = false;
    public $approvalAction = ''; // 'approve' or 'reject'
    public $canReview = false;
    public $canApprove = false;

    public function mount($sampleId)
    {
        $this->sampleId = $sampleId;
        $this->loadSampleData();

        // Load analysis results if they exist
        $this->loadAnalysisResults();

        // Build status history
        $this->buildStatusHistory();

        // Check permissions
        $this->checkPermissions();
    }

    public function loadAnalysisResults()
    {
        if ($this->reference) {
            $specifications = $this->reference->specificationsManytoMany;

            foreach ($specifications as $spec) {
                $specKey = strtolower(str_replace([' ', '-', '(', ')'], '_', $spec->name));

                // Get actual test results for this specification
                $testResults = $this->sample->testResults()
                    ->where('specification_id', $spec->id)
                    ->orderBy('reading_number')
                    ->get();

                // Use snapshot values from first test result if available, otherwise use current reference values
                $firstResult = $testResults->first();
                $operator = $firstResult->spec_operator ?? $spec->pivot->operator;
                $minValue = $firstResult->spec_min_value ?? $spec->pivot->value;
                $maxValue = $firstResult->spec_max_value ?? $spec->pivot->max_value;

                // Build specification display text from snapshot
                $specText = '';
                if ($operator && $minValue !== null) {
                    if ($operator === '-') {
                        $specText = $minValue . ' - ' . $maxValue;
                    } else {
                        $specText = $operator . ' ' . $minValue;
                    }
                }

                // Initialize the results array for this specification
                $this->analysisResults[$specKey] = [
                    'spec' => $specText ?: 'As per reference',
                    'spec_name' => $spec->name,
                    'spec_id' => $spec->id,
                    'target_value' => $minValue,
                    'operator' => $operator,
                    'max_value' => $maxValue,
                    'test_results' => [],
                    'status' => 'not_tested'
                ];

                // Process each test result for this specification
                if ($testResults->count() > 0) {
                    foreach ($testResults as $testResult) {
                        // Use snapshot values from test result for evaluation
                        $passes = $testResult->evaluateAgainstSpecification(
                            $testResult->spec_min_value ?? $minValue,
                            $testResult->spec_operator ?? $operator,
                            $testResult->spec_max_value ?? $maxValue
                        );

                        $this->analysisResults[$specKey]['test_results'][] = [
                            'id' => $testResult->id,
                            'value' => $testResult->test_value,
                            'reading_number' => $testResult->reading_number,
                            'tested_at' => $testResult->tested_at,
                            'tested_by' => $testResult->testedBy->name ?? 'Unknown',
                            'notes' => $testResult->notes,
                            'passes' => $passes,
                            'status' => $testResult->status
                        ];
                    }

                    // Determine overall status for this parameter
                    $allPassed = collect($this->analysisResults[$specKey]['test_results'])
                        ->every(fn($result) => $result['passes']);
                    $this->analysisResults[$specKey]['status'] = $allPassed ? 'pass' : 'fail';
                } else {
                    // No test results found for this specification
                    $this->analysisResults[$specKey]['status'] = 'not_tested';
                }
            }
        }
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
                return abs($testValue - $targetValue) < 0.001;
            default:
                return true;
        }
    }

    public function buildStatusHistory()
    {
        $statusHistory = [];
        $counter = 1;

        // 1. Sample Submitted/Created
        $statusHistory[] = [
            'id' => $counter++,
            'time_in' => $this->sample->created_at,
            'status' => 'Pending',
            'analyst' => $this->sample->submittedBy->name ?? 'System',
            'previous_time' => null,
        ];

        // 2. Analysis Started (if applicable)
        if ($this->sample->analysis_started_at) {
            $statusHistory[] = [
                'id' => $counter++,
                'time_in' => $this->sample->analysis_started_at,
                'status' => 'In Progress',
                'analyst' => $this->sample->primaryAnalyst->name ?? 'Unknown Analyst',
                'previous_time' => end($statusHistory)['time_in'],
            ];
        }

        // 3. Analysis Completed (if applicable)
        if ($this->sample->analysis_completed_at) {
            $statusHistory[] = [
                'id' => $counter++,
                'time_in' => $this->sample->analysis_completed_at,
                'status' => 'Analysis Completed',
                'analyst' => $this->sample->primaryAnalyst->name ?? 'Unknown Analyst',
                'previous_time' => end($statusHistory)['time_in'],
            ];
        }

        // Collect actual status events based on real timestamps and data
        $events = [];

        // Only add events that actually happened (have timestamps)

        // Review status
        if ($this->sample->reviewed_at) {
            $events[] = [
                'time' => $this->sample->reviewed_at,
                'status' => 'Review',
                'analyst' => $this->sample->reviewedBy->name ?? 'QC Manager',
                'type' => 'review'
            ];
        }

        // Approval status
        if ($this->sample->approved_at) {
            $events[] = [
                'time' => $this->sample->approved_at,
                'status' => 'Approved',
                'analyst' => $this->sample->approvedBy->name ?? 'QC Manager',
                'type' => 'approval'
            ];
        }


        // Sort events chronologically
        usort($events, function ($a, $b) {
            return $a['time']->timestamp <=> $b['time']->timestamp;
        });

        // Add sorted events to status history
        foreach ($events as $event) {
            $lastEntry = !empty($statusHistory) ? end($statusHistory) : null;
            $statusHistory[] = [
                'id' => $counter++,
                'time_in' => $event['time'],
                'status' => $event['status'],
                'analyst' => $event['analyst'],
                'previous_time' => $lastEntry ? $lastEntry['time_in'] : null,
            ];
        }

        // Handle rejected status (if applicable)
        if ($this->sample->status === 'rejected') {
            $lastEntry = end($statusHistory);
            if (!$lastEntry || $lastEntry['status'] !== 'Rejected') {
                $statusHistory[] = [
                    'id' => $counter++,
                    'time_in' => $this->sample->updated_at,
                    'status' => 'Rejected',
                    'analyst' => 'QC Manager',
                    'previous_time' => $lastEntry ? $lastEntry['time_in'] : null,
                ];
            }
        }

        $this->statusHistory = $statusHistory;
    }

    public function checkPermissions()
    {
        // Check if current user can review or approve
        // This is a basic implementation - you should adapt it to your role system
        $user = auth()->user();

        $this->canReview = in_array($this->sample->status, ['analysis_completed', 'reviewed']) &&
            $user && $user->id !== $this->sample->primary_analyst_id;

        $this->canApprove = in_array($this->sample->status, ['review', 'reviewed', 'analysis_completed']) && $user;
    }

    public function openApprovalForm($action)
    {
        $this->approvalAction = $action;
        $this->showApprovalForm = true;
        $this->reviewNotes = '';
    }

    public function closeApprovalForm()
    {
        $this->showApprovalForm = false;
        $this->approvalAction = '';
        $this->reviewNotes = '';
    }

    public function submitReview()
    {
        $this->validate([
            'reviewNotes' => 'required|string|max:1000'
        ]);

        $now = Carbon::now('Asia/Jakarta');
        $status = $this->approvalAction === 'approve' ? 'approved' : 'rejected';

        // Get status IDs
        $approvedStatus = \App\Models\Status::where('name', 'approved')->first();
        $rejectedStatus = \App\Models\Status::where('name', 'rejected')->first();

        $updateData = [
            'status_id' => $status === 'approved' ?
                ($approvedStatus ? $approvedStatus->id : null) : ($rejectedStatus ? $rejectedStatus->id : null),
            'status' => $status,
            'notes' => $this->sample->notes . "\n\nReview Notes: " . $this->reviewNotes
        ];

        if ($status === 'approved') {
            $updateData['approved_at'] = $now;
            $updateData['approved_by'] = auth()->id();

            // If sample was never reviewed, set reviewed timestamp too
            if (!$this->sample->reviewed_at) {
                $updateData['reviewed_at'] = $now;
                $updateData['reviewed_by'] = auth()->id();
            }
        } else {
            // For rejected, set rejected timestamp and still mark as reviewed if needed
            $updateData['rejected_at'] = $now;
            $updateData['rejected_by'] = auth()->id();

            if (!$this->sample->reviewed_at) {
                $updateData['reviewed_at'] = $now;
                $updateData['reviewed_by'] = auth()->id();
            }
        }

        $this->sample->update($updateData);

        $message = $this->approvalAction === 'approve' ?
            'Sample has been approved successfully!' :
            'Sample has been rejected.';

        session()->flash('message', $message);
        $this->closeApprovalForm();

        // Refresh the component
        $this->mount($this->sampleId);
    }

    public function reviewSample()
    {
        // Get reviewed status ID
        $reviewedStatus = \App\Models\Status::where('name', 'reviewed')->first();

        $this->sample->update([
            'status_id' => $reviewedStatus ? $reviewedStatus->id : null,
            'status' => 'reviewed', // Keep for backward compatibility
            'reviewed_at' => Carbon::now('Asia/Jakarta'),
            'reviewed_by' => auth()->id()
        ]);
        session()->flash('message', 'Sample marked as reviewed.');
        $this->mount($this->sampleId);
    }


    public function approveSample()
    {
        // Get approved status ID
        $approvedStatus = \App\Models\Status::where('name', 'approved')->first();

        $updateData = [
            'status_id' => $approvedStatus ? $approvedStatus->id : null,
            'status' => 'approved', // Keep for backward compatibility
            'approved_at' => Carbon::now('Asia/Jakarta'),
            'approved_by' => auth()->id(),
            'notes' => $this->sample->notes . "\n\nApproved on " . Carbon::now('Asia/Jakarta')->format('M d, Y \a\t H:i')
        ];

        // If sample was never reviewed, automatically set reviewed timestamp to maintain history
        if (!$this->sample->reviewed_at) {
            $updateData['reviewed_at'] = Carbon::now('Asia/Jakarta');
            $updateData['reviewed_by'] = auth()->id();
        }

        $this->sample->update($updateData);

        session()->flash('message', 'Sample has been approved successfully!');

        // Refresh the component
        $this->mount($this->sampleId);
    }

    public function rejectSample()
    {
        // Get rejected status ID
        $rejectedStatus = \App\Models\Status::where('name', 'rejected')->first();

        $now = Carbon::now('Asia/Jakarta');
        $updateData = [
            'status_id' => $rejectedStatus ? $rejectedStatus->id : null,
            'status' => 'rejected',
            'rejected_at' => $now,
            'rejected_by' => auth()->id(),
            'notes' => $this->sample->notes . "\n\nRejected on " . $now->format('M d, Y \a\t H:i')
        ];

        // If sample was never reviewed, automatically set reviewed timestamp to maintain history
        if (!$this->sample->reviewed_at) {
            $updateData['reviewed_at'] = $now;
            $updateData['reviewed_by'] = auth()->id();
        }

        $this->sample->update($updateData);

        session()->flash('message', 'Sample has been rejected.');

        // Refresh the component
        $this->mount($this->sampleId);
    }


    public function backToSamples()
    {
        return redirect()->route('sample-rawmat-submissions');
    }

    public function render()
    {
        return view('livewire.results-review-page')
            ->layout('layouts.app')
            ->title('Results Review - Sample #' . $this->sample->id);
    }
}
