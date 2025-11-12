<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\RawMaterialSample;
use App\Models\Status;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

/**
 * TakeOverForm Component
 *
 * Handles the take over process for samples in handover status
 * Allows analysts to accept sample ownership and continue analysis
 *
 * Features:
 * - Display sample information including handover notes
 * - Select analysis method (individual or joint)
 * - Select secondary analyst for joint analysis
 * - Take over sample and redirect to analysis page
 * - Event-driven communication with parent component
 */
class TakeOverForm extends Component
{
    public $sample;
    public $show = false;
    public $takeOverAnalysisMethod = '';
    public $takeOverSecondaryAnalystId = '';
    public $operators = [];

    protected $listeners = [
        'openTakeOverForm' => 'open',
        'closeTakeOverForm' => 'close'
    ];

    /**
     * Computed validation rules based on analysis method
     *
     * @return array
     */
    protected function rules()
    {
        $rules = [
            'takeOverAnalysisMethod' => 'required|in:individual,joint',
        ];

        if ($this->takeOverAnalysisMethod === 'joint') {
            $rules['takeOverSecondaryAnalystId'] = 'required|exists:users,id';
        }

        return $rules;
    }

    /**
     * Mount the component and load operators list
     *
     * @return void
     */
    public function mount()
    {
        // Load available operators for secondary analyst selection
        $operatorRole = Role::where('name', 'operator')->first();
        if ($operatorRole) {
            $this->operators = User::where('role_id', $operatorRole->id)
                ->orderBy('name')
                ->get();
        } else {
            $this->operators = collect();
        }
    }

    /**
     * Open the take over form modal
     *
     * @param int $sampleId
     * @return void
     */
    public function open($sampleId)
    {
        try {
            \Log::info('TakeOverForm: Opening for sample ID: ' . $sampleId);

            // Reset form first
            $this->reset(['takeOverAnalysisMethod', 'takeOverSecondaryAnalystId']);
            $this->resetErrorBag();

            // Load sample with relationships
            $this->sample = RawMaterialSample::with([
                'category',
                'rawMaterial',
                'reference',
                'statusRelation',
                'primaryAnalyst'
            ])->find($sampleId);

            if (!$this->sample) {
                throw new \Exception('Sample not found with ID: ' . $sampleId);
            }

            $this->show = true;

            \Log::info('TakeOverForm: Opened successfully', [
                'sample_id' => $this->sample->id,
                'sample_status' => $this->sample->status
            ]);

        } catch (\Exception $e) {
            $this->show = false;
            $this->sample = null;
            session()->flash('error', 'Error opening take over form: ' . $e->getMessage());
            \Log::error('TakeOverForm error: ' . $e->getMessage(), [
                'sample_id' => $sampleId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Close the take over form modal
     *
     * @return void
     */
    public function close()
    {
        $this->show = false;
        $this->sample = null;
        $this->reset(['takeOverAnalysisMethod', 'takeOverSecondaryAnalystId']);
        $this->resetErrorBag();
    }

    /**
     * Submit the take over request
     *
     * @return void
     */
    public function submitTakeOver()
    {
        $this->validate();

        try {
            $inProgressStatus = Status::where('name', 'in_progress')->first();

            $updateData = [
                'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
                'status' => 'in_progress',
                'primary_analyst_id' => auth()->id(),
                'analysis_method' => $this->takeOverAnalysisMethod,
                'handover_taken_by' => auth()->id(),
                'handover_taken_at' => Carbon::now('Asia/Jakarta'),
                'handover_to_analyst_id' => null, // Clear hand over assignment
            ];

            // Store original data if first time starting analysis
            if (!$this->sample->original_primary_analyst_id) {
                $updateData['original_primary_analyst_id'] = $this->sample->primary_analyst_id;
                $updateData['original_secondary_analyst_id'] = $this->sample->secondary_analyst_id;
                $updateData['original_analysis_method'] = $this->sample->analysis_method;
            }

            if ($this->takeOverAnalysisMethod === 'joint') {
                $updateData['secondary_analyst_id'] = $this->takeOverSecondaryAnalystId;
            } else {
                $updateData['secondary_analyst_id'] = null;
            }

            $this->sample->update($updateData);

            $message = "Sample taken over successfully! Analysis method: " . ucfirst($this->takeOverAnalysisMethod);
            if ($this->takeOverAnalysisMethod === 'joint') {
                $secondaryAnalyst = User::find($this->takeOverSecondaryAnalystId);
                $message .= " with " . $secondaryAnalyst->name;
            }

            session()->flash('message', $message);

            // Dispatch event to parent to refresh the list
            $this->dispatch('takeOverSubmitted');

            $this->close();

            // Redirect to analysis page
            return redirect()->route('analysis-page', ['sampleId' => $this->sample->id]);

        } catch (\Exception $e) {
            session()->flash('error', 'Error taking over sample: ' . $e->getMessage());
            \Log::error('TakeOverForm submitTakeOver error: ' . $e->getMessage());
        }
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.components.take-over-form');
    }
}
