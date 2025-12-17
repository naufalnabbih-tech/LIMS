<?php

namespace App\Livewire\Components;

use Livewire\Component;

/**
 * FlashMessages Component
 *
 * Reusable component for displaying flash messages (success and error)
 * with auto-dismiss functionality using Alpine.js
 */
class FlashMessages extends Component
{
    public $message = '';
    public $type = '';

    protected $listeners = [
        'flash-message' => 'handleFlashMessage'
    ];

    public function handleFlashMessage($data)
    {
        $this->type = $data['type'] ?? 'success';
        $this->message = $data['message'] ?? '';
    }

    public function render()
    {
        return view('livewire.components.flash-messages');
    }
}
