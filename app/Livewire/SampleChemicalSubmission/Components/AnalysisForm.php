<?php

namespace App\Livewire\SampleChemicalSubmission\Components;

use App\Models\Sample;
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
        $this->selectedAnalysisSample = Sample::with([
            'category',
            'material',
            'reference',
            'status'
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

        $messages = [
            'analysisMethod.required' => 'Metode analisis wajib dipilih.',
            'analysisMethod.in' => 'Metode analisis tidak valid.',
            'secondaryAnalystId.required' => 'Secondary analyst wajib dipilih untuk metode joint analysis.',
            'secondaryAnalystId.exists' => 'Secondary analyst yang dipilih tidak valid.',
        ];

        if ($this->analysisMethod === 'joint') {
            $rules['secondaryAnalystId'] = 'required|exists:users,id';
        }

        $this->validate($rules, $messages);

        // Always set current user as primary analyst (for both individual and joint)
        $this->primaryAnalystId = auth()->id();

        $sample = $this->selectedAnalysisSample;

        $inProgressStatus = Status::where('name', 'in_progress')->first();

        // Update sample status and analysis information
        $analysisData = [
            'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
            'analysis_method' => $this->analysisMethod,
            'primary_analyst_id' => $this->primaryAnalystId,
            'analysis_started_at' => Carbon::now('Asia/Jakarta'),
        ];

        if ($this->analysisMethod === 'joint') {
            $analysisData['secondary_analyst_id'] = $this->secondaryAnalystId;
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
        return view('livewire.sample-chemical-submission.components.analysis-form');  // ✅ GANTI VIEW PATH
    }
}
