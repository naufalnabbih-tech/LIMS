<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SampleHandover;
use Livewire\Attributes\On;

class HandoverToastNotification extends Component
{
    public $toasts = [];
    public $pendingHandoversCount = 0;
    public $myHandoversCount = 0;

    public function mount()
    {
        $this->loadCounts();
    }

    #[On('handover-updated')]
    public function loadCounts()
    {
        // Get counts for notifications
        $this->pendingHandoversCount = SampleHandover::where('status', 'pending')
            ->whereNull('to_analyst_id')
            ->where('from_analyst_id', '!=', auth()->id())
            ->count();

        $this->myHandoversCount = SampleHandover::where('from_analyst_id', auth()->id())
            ->where('status', 'pending')
            ->count();
    }

    #[On('show-handover-toast')]
    public function showToast($type, $message, $details = [])
    {
        $toastId = uniqid('toast_');

        $this->toasts[] = [
            'id' => $toastId,
            'type' => $type, // 'pending' or 'submitted'
            'message' => $message,
            'details' => $details,
            'timestamp' => now()->toIso8601String()
        ];

        // Auto-remove toast after 5 seconds
        $this->dispatch('remove-toast-after-delay', toastId: $toastId, delay: 3000);
    }

    public function removeToast($toastId)
    {
        $this->toasts = array_filter($this->toasts, function($toast) use ($toastId) {
            return $toast['id'] !== $toastId;
        });
    }

    public function render()
    {
        return view('livewire.handover-toast-notification');
    }
}
