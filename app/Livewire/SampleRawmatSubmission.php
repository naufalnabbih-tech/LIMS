<?php

namespace App\Livewire;

use App\Models\RawMaterialSample;
use App\Models\RawMatCategory;
use App\Models\RawMat;
use App\Models\Reference;
use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SampleRawmatSubmission extends Component
{
    use WithPagination, WithFileUploads;

    public $category_id = '';
    public $raw_mat_id = '';
    public $reference_id = '';
    public $supplier = '';
    public $batch_lot = '';
    public $vehicle_container_number = '';
    public $has_coa = false;
    public $coa_file = null;
    public $submission_date = '';
    public $submission_time = '';
    public $notes = '';

    public $categories = [];
    public $rawMaterials = [];
    public $references = [];
    public $showForm = false;
    public $showDetails = false;
    public $selectedSample = null;
    
    // Analysis form properties
    public $showAnalysisForm = false;
    public $selectedAnalysisSample = null;
    public $analysisMethod = '';
    public $primaryAnalystId = '';
    public $secondaryAnalystId = '';
    public $analysts = [];
    public $operators = [];
    public $statuses = [];

    // All form properties now handled by child components

    protected $listeners = [
        'sampleUpdated' => '$refresh',
        'printSampleLabel' => 'handlePrintLabel',
        'handOverSubmitted' => '$refresh',
        'takeOverSubmitted' => '$refresh'
    ];

    protected $rules = [
        'category_id' => 'required|exists:raw_mat_categories,id',
        'raw_mat_id' => 'required|exists:raw_mats,id',
        'reference_id' => 'required|exists:references,id',
        'supplier' => 'required|string|max:255',
        'batch_lot' => 'required|string|max:255',
        'vehicle_container_number' => 'required|string|max:255',
        'has_coa' => 'boolean',
        'coa_file' => 'nullable|required_if:has_coa,true|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        'submission_date' => 'required|date',
        'submission_time' => 'required',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->categories = RawMatCategory::all();
        $this->rawMaterials = collect();
        $this->references = collect();
        $this->submission_date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->submission_time = Carbon::now('Asia/Jakarta')->format('H:i');
        $this->analysts = User::all(); // Load all users as potential analysts

        // Load operators specifically for joint analysis
        $operatorRole = Role::where('name', 'operator')->first();
        if ($operatorRole) {
            $this->operators = User::where('role_id', $operatorRole->id)->get();
        } else {
            $this->operators = collect();
        }

        // Load all active statuses
        $this->statuses = Status::active()->ordered()->get();
    }

    public function updatedCategoryId($value)
    {
        $this->raw_mat_id = '';
        $this->reference_id = '';
        if ($value) {
            $this->rawMaterials = RawMat::where('category_id', $value)->get();
        } else {
            $this->rawMaterials = collect();
        }
        $this->references = collect();
    }

    public function updatedRawMatId($value)
    {
        $this->reference_id = '';
        if ($value) {
            $this->references = Reference::where('rawmat_id', $value)->get();
        } else {
            $this->references = collect();
        }
    }


    public function showCreateForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function hideForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->category_id = '';
        $this->raw_mat_id = '';
        $this->reference_id = '';
        $this->supplier = '';
        $this->batch_lot = '';
        $this->vehicle_container_number = '';
        $this->has_coa = false;
        $this->coa_file = null;
        $this->submission_date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->submission_time = Carbon::now('Asia/Jakarta')->format('H:i');
        $this->notes = '';
        $this->rawMaterials = collect();
        $this->references = collect();
        $this->resetErrorBag();
    }

    public function submit()
    {
        try {
            $this->validate([
                'category_id' => 'required|exists:raw_mat_categories,id',
                'raw_mat_id' => 'required|exists:raw_mats,id',
                'reference_id' => 'required|exists:references,id',
                'supplier' => 'required|string|max:255',
                'batch_lot' => 'required|string|max:255',
                'vehicle_container_number' => 'required|string|max:255',
                'has_coa' => 'boolean',
                'coa_file' => 'nullable|required_if:has_coa,true|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
                'submission_date' => 'required|date',
                'submission_time' => 'required',
                'notes' => 'nullable|string|max:1000',
            ]);

        $coaFilePath = null;
        if ($this->has_coa && $this->coa_file) {
            $coaFilePath = $this->coa_file->store('coa-files', 'public');
        }

        // Get pending status ID
        $pendingStatus = Status::where('name', 'pending')->first();

        RawMaterialSample::create([
            'category_id' => $this->category_id,
            'raw_mat_id' => $this->raw_mat_id,
            'reference_id' => $this->reference_id,
            'supplier' => $this->supplier,
            'batch_lot' => $this->batch_lot,
            'vehicle_container_number' => $this->vehicle_container_number,
            'has_coa' => $this->has_coa,
            'coa_file_path' => $coaFilePath,
            'submission_time' => $this->submission_date . ' ' . Carbon::now('Asia/Jakarta')->format('H:i:s'),
            'entry_time' => Carbon::now('Asia/Jakarta'),
            'submitted_by' => auth()->id(),
            'status_id' => $pendingStatus ? $pendingStatus->id : null,
            'status' => 'pending', // Keep for backward compatibility during transition
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Raw material sample submitted successfully!');
        $this->hideForm();
        } catch (\Exception $e) {
            session()->flash('error', 'Error submitting sample: ' . $e->getMessage());
            \Log::error('Sample submission error: ' . $e->getMessage());
        }
    }

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

    // Analysis form methods
    public function openAnalysisForm($sampleId)
    {
        $this->resetAnalysisForm();
        $this->selectedAnalysisSample = RawMaterialSample::with(['category', 'rawMaterial', 'reference', 'statusRelation'])->findOrFail($sampleId);
        $this->showAnalysisForm = true;
    }

    public function hideAnalysisForm()
    {
        $this->showAnalysisForm = false;
        $this->selectedAnalysisSample = null;
        $this->resetAnalysisForm();
    }

    public function resetAnalysisForm()
    {
        $this->analysisMethod = '';
        $this->primaryAnalystId = '';
        $this->secondaryAnalystId = '';
        $this->resetErrorBag();
    }

    public function startAnalysisProcess()
    {
        // Validate the form
        $rules = [
            'analysisMethod' => 'required|in:individual,joint',
        ];

        if ($this->analysisMethod === 'joint') {
            $rules['secondaryAnalystId'] = 'required|exists:users,id';
        }

        $this->validate($rules);

        // Always set current user as primary analyst (for both individual and joint)
        $this->primaryAnalystId = auth()->id();

        $sample = $this->selectedAnalysisSample;
        
        // Get status IDs
        $inProgressStatus = Status::where('name', 'in_progress')->first();
        $inProgressRestartStatus = Status::where('name', 'in_progress_restart')->first();

        // Determine the correct status based on current sample status
        $currentStatusName = $sample->statusRelation ? $sample->statusRelation->name : $sample->status;
        $newStatusId = $currentStatusName === 'restart_analysis' ?
            ($inProgressRestartStatus ? $inProgressRestartStatus->id : null) :
            ($inProgressStatus ? $inProgressStatus->id : null);

        // Update sample status and analysis information
        $analysisData = [
            'status_id' => $newStatusId,
            'analysis_method' => $this->analysisMethod,
            'primary_analyst_id' => $this->primaryAnalystId,
            'analysis_started_at' => Carbon::now('Asia/Jakarta'),
        ];

        // Store original data for first time analysis start
        if (!$sample->original_primary_analyst_id) {
            $analysisData['original_primary_analyst_id'] = $this->primaryAnalystId;
            $analysisData['original_analysis_method'] = $this->analysisMethod;
        }

        if ($this->analysisMethod === 'joint') {
            $analysisData['secondary_analyst_id'] = $this->secondaryAnalystId;
            // Store original secondary analyst if first time
            if (!$sample->original_secondary_analyst_id) {
                $analysisData['original_secondary_analyst_id'] = $this->secondaryAnalystId;
            }
        }

        $sample->update($analysisData);

        $primaryAnalyst = User::find($this->primaryAnalystId);
        $message = "Analysis started for sample - Assigned to: " . $primaryAnalyst->name;
        
        if ($this->analysisMethod === 'joint') {
            $secondaryAnalyst = User::find($this->secondaryAnalystId);
            $message .= " & " . $secondaryAnalyst->name;
        }

        session()->flash('message', $message);
        $this->hideAnalysisForm();
        
        // Redirect to the analysis page using Livewire's redirect method
        $this->redirect(route('analysis-page', ['sampleId' => $sample->id]));
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
