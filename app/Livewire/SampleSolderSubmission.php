<?php

namespace App\Livewire;


use App\Models\Sample;
use App\Models\Category;
use App\Models\Material;
use App\Models\Reference;
use App\Models\SampleHandover;
use App\Models\Status;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SampleSolderSubmission extends Component
{
    use WithPagination;

    protected $listeners = [
        'sampleUpdated' => '$refresh',
        'sampleCreated' => '$refresh',
        'analysisStarted' => '$refresh',
        'printSampleLabel' => 'handlePrintLabel',
        'handOverSubmitted' => '$refresh',
        'takeOverSubmitted' => '$refresh'
    ];

    // Action Methods - now dispatch events to child components
    public function viewDetails($sampleId)
    {
        $this->dispatch('openSampleDetails', sampleId: $sampleId);
    }

    public function editSample($sampleId)
    {
        $this->dispatch('editSample', sampleId: $sampleId);
    }

    public function handlePrintLabel($sampleId)
    {
        $sample = Sample::with([
            'category',
            'material',
            'reference',
            'submittedBy',
            'status'
        ])->findOrFail($sampleId);

        try {
            // Prepare label data
            $labelData = [
                'sample_id' => $sample->id,
                'material_name' => $sample->material->name ?? 'N/A',
                'category_name' => $sample->category->name ?? 'N/A',
                'supplier' => $sample->supplier,
                'batch_lot' => $sample->batch_lot,
                'submission_date' => $sample->submission_time->format('Y-m-d H:i'),
                'reference' => $sample->reference->name ?? 'N/A',
                'vehicle_container' => $sample->vehicle_container_number,
                'status' => $sample->status ? $sample->status->display_name : 'N/A',
                'submitted_by' => $sample->submittedBy->name ?? 'N/A',
            ];

            // Call JavaScript function directly
            $this->js('printSampleLabel(' . json_encode($labelData) . ')');

        } catch (\Exception $e) {
            session()->flash('error', 'Error preparing sample label: ' . $e->getMessage());
        }
    }

    // Analysis form methods - now dispatches to AnalysisForm component
    public function openAnalysisForm($sampleId)
    {
        $this->dispatch('openAnalysisForm', sampleId: $sampleId);
    }

    public function startAnalysis($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);
        $inProgressStatus = Status::where('name', 'in_progress')->first();

        $sample->update([
            'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
            'analysis_started_at' => Carbon::now('Asia/Jakarta'),
            'primary_analyst_id' => auth()->id()
        ]);

        session()->flash('message', "Analysis started for sample");

        return redirect()->route('analysis-page', ['sampleId' => $sampleId]);
    }

    public function continueAnalysis($sampleId)
    {
        return redirect()->route('analysis-page', ['sampleId' => $sampleId]);
    }

    public function completeAnalysis($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);
        $analysisCompletedStatus = Status::where('name', 'analysis_completed')->first();

        $sample->update([
            'status_id' => $analysisCompletedStatus ? $analysisCompletedStatus->id : null,
            'analysis_completed_at' => Carbon::now('Asia/Jakarta')
        ]);
        session()->flash('message', "Analysis completed for sample");
    }

    public function reviewResults($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);

        $currentStatusName = $sample->status ? $sample->status->name : '';

        if ($currentStatusName === 'approved') {
            return;
        }

        $reviewStatus = Status::where('name', 'reviewed')->first();

        $updateData = [
            'reviewed_at' => Carbon::now('Asia/Jakarta'),
            'reviewed_by' => auth()->id(),
            'status_id' => $reviewStatus ? $reviewStatus->id : null
        ];

        $sample->update($updateData);
    }

    public function approveSample($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);
        $approvedStatus = Status::where('name', 'approved')->first();

        $updateData = [
            'status_id' => $approvedStatus ? $approvedStatus->id : null,
            'approved_at' => Carbon::now('Asia/Jakarta'),
            'approved_by' => auth()->id()
        ];

        // If sample was never reviewed, automatically set reviewed timestamp to maintain history
        if (!$sample->reviewed_at) {
            $updateData['reviewed_at'] = Carbon::now('Asia/Jakarta');
            $updateData['reviewed_by'] = auth()->id();
        }

        $sample->update($updateData);
        session()->flash('message', "Sample has been approved");
    }

    public function deleteSample($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);

        // Delete associated CoA file if exists
        if ($sample->coa_file_path) {
            \Storage::disk('public')->delete($sample->coa_file_path);
        }

        $sample->delete();
        session()->flash('message', "Sample has been deleted successfully");
    }

    // Hand Over Methods
    public function openHandOverForm($sampleId)
    {
        $this->dispatch('openHandOverForm', sampleId: $sampleId);
    }

    public function openTakeOverForm($sampleId)
    {
        $this->dispatch('openTakeOverForm', sampleId: $sampleId);
    }

    // Take Over Methods
    public function takeSample($handoverId)
    {
        $handover = SampleHandover::with(['sample', 'toAnalyst'])->findOrFail($handoverId);

        if($handover->to_analyst_id != auth()->id()){
            session()->flash('error', 'You are not authorized to take this sample.');
            return;
        }

        if(!$handover->isPending()){
            session()->flash('error', 'This sample has already been taken over.');
            return;
        }

        $inProgressStatus = Status::where('name', 'in_progress')->first();

        // For Update Sample
        $handover->sample->update([
            'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
            'primary_analyst_id' => auth()->id()
        ]);

        // For Update Handover
        $handover->update([
            'status' => 'accepted',
            'taken_at' => Carbon::now('Asia/Jakarta'),
            'taken_by' => auth()->id(),
        ]);

        session()->flash('message', 'Sample taken successfully. You are now the assigned analyst.');
        $this->dispatch('takeOverSubmitted');
    }

    public function render()
    {
        $samples = Sample::with(['category', 'material', 'reference', 'submittedBy', 'status'])
            ->where('sample_type', 'solder')
            ->where(function($query) {
                // Show all samples that are NOT in_progress
                $query->whereHas('status', function($q) {
                    $q->where('name', '!=', 'in_progress');
                })
                // OR show in_progress samples only if user is primary or secondary analyst
                ->orWhere(function($q) {
                    $q->whereHas('status', function($q2) {
                        $q2->where('name', 'in_progress');
                    })
                    ->where(function($q2) {
                        $q2->where('primary_analyst_id', auth()->id())
                          ->orWhere('secondary_analyst_id', auth()->id());
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Show all pending handovers (anyone can take over, except own handovers)
        $pendingHandovers = SampleHandover::with(['sample.material', 'fromAnalyst'])
            ->where('status', 'pending')
            ->whereNull('to_analyst_id') // Only handovers without assigned analyst
            ->where('from_analyst_id', '!=', auth()->id()) // Exclude own handovers
            ->whereHas('sample', function($q){
                $q->where('sample_type', 'solder');
            })
            ->latest()
            ->get();

        // Show handovers submitted by current user
        $myHandovers = SampleHandover::with(['sample.material', 'toAnalyst'])
            ->where('from_analyst_id', auth()->id())
            ->where('status', 'pending')
            ->whereHas('sample', function($q){
                $q->where('sample_type', 'solder');
            })
            ->latest()
            ->get();

        return view('livewire.sample-solder-submission', [
            'samples' => $samples,
            'pendingHandovers' => $pendingHandovers,
            'myHandovers' => $myHandovers,
        ])->layout('layouts.app')->title('Sample Solder Submission');
    }
}
