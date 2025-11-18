<?php

namespace App\Livewire;

use App\Models\RawMaterialSample;
use App\Models\Status;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SampleRawmatSubmission extends Component
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
        $sample = RawMaterialSample::with([
            'category',
            'rawMaterial',
            'reference',
            'submittedBy',
            'statusRelation'
        ])->findOrFail($sampleId);

        try {
            // Prepare label data
            $labelData = [
                'sample_id' => $sample->id,
                'material_name' => $sample->rawMaterial->name ?? 'N/A',
                'category_name' => $sample->category->name ?? 'N/A',
                'supplier' => $sample->supplier,
                'batch_lot' => $sample->batch_lot,
                'submission_date' => $sample->submission_time->format('Y-m-d H:i'),
                'reference' => $sample->reference->name ?? 'N/A',
                'vehicle_container' => $sample->vehicle_container_number,
                'status' => $sample->statusRelation ? $sample->statusRelation->display_name : ucfirst($sample->status ?? ''),
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
        $sample = RawMaterialSample::with('statusRelation')->findOrFail($sampleId);
        $inProgressStatus = Status::where('name', 'in_progress')->first();

        $sample->update([
            'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
            'analysis_started_at' => Carbon::now('Asia/Jakarta'),
            'primary_analyst_id' => auth()->id()
        ]);
        session()->flash('message', "Analysis started for sample");

        // Redirect to the analysis page
        return redirect()->route('analysis-page', ['sampleId' => $sampleId]);
    }

    public function continueAnalysis($sampleId)
    {
        // Redirect to the analysis page for ongoing analysis
        return redirect()->route('analysis-page', ['sampleId' => $sampleId]);
    }

    public function completeAnalysis($sampleId)
    {
        $sample = RawMaterialSample::with('statusRelation')->findOrFail($sampleId);
        $analysisCompletedStatus = Status::where('name', 'analysis_completed')->first();

        $sample->update([
            'status_id' => $analysisCompletedStatus ? $analysisCompletedStatus->id : null,
            'analysis_completed_at' => Carbon::now('Asia/Jakarta')
        ]);
        session()->flash('message', "Analysis completed for sample");
    }

    public function reviewResults($sampleId)
    {
        $sample = RawMaterialSample::with('statusRelation')->findOrFail($sampleId);

        // Get current status name
        $currentStatusName = $sample->statusRelation ? $sample->statusRelation->name : ($sample->status ?? '');

        // For approved samples, don't make any changes to preserve history
        if ($currentStatusName === 'approved') {
            return; // Just redirect to review page without updating database
        }

        $reviewStatus = Status::where('name', 'reviewed')->first();

        // Update status to 'reviewed'
        $updateData = [
            'reviewed_at' => Carbon::now('Asia/Jakarta'),
            'reviewed_by' => auth()->id(),
            'status_id' => $reviewStatus ? $reviewStatus->id : null
        ];

        $sample->update($updateData);
    }


    public function approveSample($sampleId)
    {
        $sample = RawMaterialSample::with('statusRelation')->findOrFail($sampleId);
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
        $sample = RawMaterialSample::with('statusRelation')->findOrFail($sampleId);

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


    public function acceptHandOver($sampleId)
    {
        $sample = RawMaterialSample::with('statusRelation')->findOrFail($sampleId);

        // Check if current user is the designated next analyst
        if ($sample->handover_to_analyst_id != auth()->id()) {
            session()->flash('error', 'You are not authorized to accept this hand over.');
            return;
        }

        $inProgressStatus = Status::where('name', 'in_progress')->first();

        $sample->update([
            'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
            'status' => 'in_progress',
            'primary_analyst_id' => auth()->id(), // Transfer analyst
            'handover_to_analyst_id' => null, // Clear hand over
            'handover_notes' => null, // Clear notes
        ]);

        session()->flash('message', "Hand over accepted. You are now the assigned analyst.");
    }

    // Take Over Methods
    public function openTakeOverForm($sampleId)
    {
        $this->dispatch('openTakeOverForm', sampleId: $sampleId);
    }


    public function render()
    {
        $samples = RawMaterialSample::with(['category', 'rawMaterial', 'reference', 'submittedBy', 'statusRelation'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.sample-rawmat-submission', [
            'samples' => $samples
        ])->layout('layouts.app')->title('Sample Raw Material Submission');
    }
}
