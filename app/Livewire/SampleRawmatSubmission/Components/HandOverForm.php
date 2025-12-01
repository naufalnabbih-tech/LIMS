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

    public $reason = '';
    public $notes = '';

    protected $listeners = [
        'openHandOverForm' => 'show',
    ];

    protected $rules = [
        'reason' => 'required|string|max:255',
        'notes' => 'nullable|string|max:1000',
    ];

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

    public function resetForm(){
        $this->reason = '';
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function submitHandOver(){
        try {
            $this->validate();

            $sample = $this->selectedSample;

            // Refresh the sample to get latest status
            $sample = Sample::with('status')->findOrFail($sample->id);

            // Check if sample is in progress
            if(!$sample->isInAnalysis()){
                $statusName = $sample->status ? $sample->status->name : 'No Status';
                $this->addError('to_analyst_id', "Only samples in progress can be handed over. Current status: {$statusName}");
                return;
            }

            //Check if user is primary analyst
            if($sample->primary_analyst_id != auth()->id()){
                $this->addError('to_analyst_id', 'Only the primary analyst can hand over this sample.');
                return;
            }

            // Check if sample already has  active handover
            if($sample->hasActiveHandover()){
                $this->addError('to_analyst_id', 'This sample already has an active handover.');
                return;
            }

        // Get Hand Over Status
        $handOverStatus = Status::where('name', 'hand_over')->first();

        // Create Hand Over Record
        SampleHandover::create([
            'sample_id' => $sample->id,
            'from_analyst_id' => auth()->id(),
            'to_analyst_id' => null, // No specific analyst, anyone can take over
            'reason' => $this->reason,
            'notes' => $this->notes,
            'submitted_at' => Carbon::now('Asia/Jakarta'),
            'submitted_by' => auth()->id(),
            'status' => 'pending'
        ]);

        //Update sample status to "Hand Over"
        $sample->update([
            'status_id' => $handOverStatus ? $handOverStatus->id : null,
        ]);

            session()->flash('message', 'Sample handed over successfully. Waiting for another operator to take over.');

            // Dispatch toast notification
            $this->dispatch('show-handover-toast',
                type: 'submitted',
                message: 'Your sample has been submitted for hand over. Waiting for another analyst to take over.',
                details: [
                    'material' => $sample->material->name ?? 'N/A',
                    'batch' => $sample->batch_lot
                ]
            );

            $this->hide();
            $this->dispatch('handOverSubmitted');
            $this->dispatch('handover-updated');
        } catch (\Exception $e) {
            \Log::error('HandOverForm submitHandOver error: ' . $e->getMessage(), [
                'sample_id' => $this->selectedSample->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('reason', 'Error submitting hand over: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.hand-over-form');
    }
}
