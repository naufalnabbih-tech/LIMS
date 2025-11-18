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
    public function render()
    {
        return view('livewire.components.flash-messages');
    }
}
