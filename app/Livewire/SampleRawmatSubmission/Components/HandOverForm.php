<?php

namespace App\Livewire\SampleRawmatSubmission\Components;

use App\Models\Sample;
use App\Models\SampleHandover;
use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use Livewire\Component;
use Carbon\Carbon;

/**
 * HandOverForm Component
 *
 * Handles the hand over process for samples in progress
 * Allows analysts to transfer sample ownership to another analyst
 *
 * Features:
 * - Display sample information
 * - Add hand over notes for the next analyst
 * - Submit sample for hand over
 * - Event-driven communication with parent component
 */
class HandOverForm extends Component
{
    public $showHandOverForm = false;
    public $selectedSample = null;

    public $to_analyst_id = '';
    public $reason = '';
    public $notes = '';

    public $operators = [];

    protected $listeners = [
        'openHandOverForm' => 'show',
    ];


    protected $rules = [
        'to_analyst_id' => 'required|exists:users,id',
        'reason' => 'required|string|max:255',
        'notes' => 'nullable|string|max:1000 ',
    ];


    public function mount(){
        $operatorRole = Role::where('name', 'operator')->first();
        if($operatorRole){
            $this->operators = User::where('role_id', $operatorRole->id)
                ->where('id', '!=', auth()->id())
                ->get();
        }else{
            $this->operators = collect();
        }
    }

    public function show($sampleId){
        $this->resetForm();
        $this->selectedSample = Sample::with([
            'category',
            'material',
            'reference',
            'status'
        ])->findOrFail($sampleId);
        $this->showHandOverForm = true;
    }

    public function hide(){
        $this->showHandOverForm = false;
        $this->selectedSample = null;
        $this->resetForm();
    }

    public function open($sampleId)
    {
        try {
            \Log::info('HandOverForm: Opening for sample ID: ' . $sampleId);

            // Reset form first
            $this->reset(['handOverNotes']);
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

            \Log::info('HandOverForm: Opened successfully', [
                'sample_id' => $this->sample->id,
                'sample_status' => $this->sample->status
            ]);

        } catch (\Exception $e) {
            $this->show = false;
            $this->sample = null;
            session()->flash('error', 'Error opening hand over form: ' . $e->getMessage());
            \Log::error('HandOverForm error: ' . $e->getMessage(), [
                'sample_id' => $sampleId,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Close the hand over form modal
     *
     * @return void
     */
    public function close()
    {
        $this->show = false;
        $this->sample = null;
        $this->reset(['handOverNotes']);
        $this->resetErrorBag();
    }

    /**
     * Submit the sample for hand over
     *
     * @return void
     */
    public function submitToHandOver()
    {
        $this->validate();

        try {
            $submittedToHandOverStatus = Status::where('name', 'submitted_to_handover')->first();

            // Store original data if not already stored
            $updateData = [
                'status_id' => $submittedToHandOverStatus ? $submittedToHandOverStatus->id : null,
                'status' => 'submitted_to_handover',
                'handover_notes' => $this->handOverNotes,
                'handover_submitted_by' => auth()->id(),
                'handover_submitted_at' => Carbon::now('Asia/Jakarta'),
                'handover_to_analyst_id' => null, // Clear specific analyst assignment
            ];

            // Store original data if first time starting analysis
            if (!$this->sample->original_primary_analyst_id) {
                $updateData['original_primary_analyst_id'] = $this->sample->primary_analyst_id;
                $updateData['original_secondary_analyst_id'] = $this->sample->secondary_analyst_id;
                $updateData['original_analysis_method'] = $this->sample->analysis_method;
            }

            $this->sample->update($updateData);

            session()->flash('message', "Sample submitted for hand over. Any analyst can now take over this sample.");

            // Dispatch event to parent to refresh the list
            $this->dispatch('handOverSubmitted');

            $this->close();

        } catch (\Exception $e) {
            session()->flash('error', 'Error submitting hand over: ' . $e->getMessage());
            \Log::error('HandOverForm submitToHandOver error: ' . $e->getMessage());
        }
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.hand-over-form');
    }
}
