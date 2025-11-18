<?php

namespace App\Livewire\SampleRawmatSubmission\Components;

use App\Models\RawMaterialSample;
use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use Livewire\Component;
use Carbon\Carbon;

class AnalysisForm extends Component
{
    // Form visibility
    public $showAnalysisForm = false;

    // Selected sample
    public $selectedAnalysisSample = null;

    // Form fields
    public $analysisMethod = '';
    public $primaryAnalystId = '';
    public $secondaryAnalystId = '';

    // Data collections
    public $operators = [];

    protected $listeners = [
        'openAnalysisForm' => 'show',
    ];

    public function mount()
    {
        // Load operators specifically for joint analysis
        $operatorRole = Role::where('name', 'operator')->first();
        if ($operatorRole) {
            $this->operators = User::where('role_id', $operatorRole->id)->get();
        } else {
            $this->operators = collect();
        }
    }

    public function show($sampleId)
    {
        $this->resetForm();
        $this->selectedAnalysisSample = RawMaterialSample::with([
            'category',
            'rawMaterial',
            'reference',
            'statusRelation'
        ])->findOrFail($sampleId);
        $this->showAnalysisForm = true;
    }

    public function hide()
    {
        $this->showAnalysisForm = false;
        $this->selectedAnalysisSample = null;
        $this->resetForm();
    }

    public function resetForm()
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
        $this->hide();
        $this->dispatch('analysisStarted');

        // Redirect to the analysis page using Livewire's redirect method
        $this->redirect(route('analysis-page', ['sampleId' => $sample->id]));
    }

    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.analysis-form');
    }
}
