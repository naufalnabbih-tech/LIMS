<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SampleHandover;
use Livewire\Attributes\On;

class NotificationDropdown extends Component
{
    public $pendingHandovers = [];
    public $myHandovers = [];
    public $totalNotifications = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    #[On('handover-updated')]
    public function loadNotifications()
    {
        // Get pending handovers (samples waiting to be taken over by current user)
        // Exclude handovers submitted by current user
        $this->pendingHandovers = SampleHandover::with(['sample.material', 'fromAnalyst'])
            ->where('status', 'pending')
            ->whereNull('to_analyst_id')
            ->where('from_analyst_id', '!=', auth()->id())
            ->latest()
            ->get();

        // Get handovers submitted by current user (waiting to be taken)
        $this->myHandovers = SampleHandover::with(['sample.material', 'toAnalyst'])
            ->where('from_analyst_id', auth()->id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Calculate total notifications
        $this->totalNotifications = $this->pendingHandovers->count() + $this->myHandovers->count();
    }

    public function openTakeOverModal($handoverId)
    {
        // Navigate to the appropriate page with the sample
        $handover = SampleHandover::with('sample')->find($handoverId);

        if ($handover && $handover->sample) {
            // Determine the sample type to navigate to correct page
            if ($handover->sample->sample_type === 'raw_material') {
                return redirect()->route('sample-rawmat-submissions');
            } else {
                return redirect()->route('sample-solder-submissions');
            }
        }
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}
