<?php

namespace App\Livewire\Components;

use Livewire\Component;

/**
 * EmptyState Component
 *
 * Reusable component for displaying empty states with customizable
 * title, description, and action button
 */
class EmptyState extends Component
{
    public string $title = 'No items found';
    public string $description = 'Get started by creating your first item';
    public string $buttonText = 'Create Item';
    public string $buttonEvent = 'openCreateForm';
    public bool $showButton = true;
    public string $icon = 'document'; // document, folder, inbox, etc.

    public function render()
    {
        return view('livewire.components.empty-state');
    }
}
