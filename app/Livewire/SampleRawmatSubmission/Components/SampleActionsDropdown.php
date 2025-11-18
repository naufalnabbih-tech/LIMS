<?php

namespace App\Livewire\SampleRawmatSubmission\Components;

use Livewire\Component;

/**
 * SampleActionsDropdown Component
 *
 * Provides a context menu dropdown for sample actions including:
 * - View details
 * - Edit sample
 * - Start/continue analysis
 * - Submit to handover
 * - Take over sample
 * - Review results
 * - Approve sample
 * - Delete sample
 *
 * Uses Alpine.js for positioning, transitions, and state management
 * Communicates with parent component via Livewire events
 */
class SampleActionsDropdown extends Component
{
    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.sample-actions-dropdown');
    }
}
