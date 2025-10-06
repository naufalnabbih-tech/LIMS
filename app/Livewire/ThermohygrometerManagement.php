<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Thermohygrometer;
use Illuminate\Validation\Rule;

class ThermohygrometerManagement extends Component
{
    use WithPagination;

    // Modal states
    public bool $isAddModalOpen = false;
    public bool $isEditModalOpen = false;

    // Form data
    public $name = '';
    public $description = '';
    public $editingId = null;

    // Loading states
    public $isSubmitting = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:thermohygrometers,name',
            'description' => 'nullable|string|max:1000',
        ];

        // For edit, exclude current record from unique validation
        if ($this->editingId) {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('thermohygrometers', 'name')->ignore($this->editingId)
            ];
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Thermohygrometer name is required.',
        'name.unique' => 'This thermohygrometer name already exists.',
        'name.max' => 'Thermohygrometer name cannot exceed 255 characters.',
        'description.max' => 'Description cannot exceed 1000 characters.',
    ];

    public function openAddModal()
    {
        $this->resetForm();
        $this->isAddModalOpen = true;
    }

    public function closeAddModal()
    {
        $this->isAddModalOpen = false;
        $this->resetForm();
    }

    public function openEditModal($id, $name, $description)
    {
        $this->resetForm();
        $this->editingId = $id;
        $this->name = $name;
        $this->description = $description;
        $this->isEditModalOpen = true;
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->editingId = null;
        $this->isSubmitting = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->isSubmitting = true;

        $this->validate();

        try {
            Thermohygrometer::create([
                'name' => $this->name,
                'description' => $this->description
            ]);

            $this->closeAddModal();
            $this->resetPage();
            session()->flash('success', 'Thermohygrometer created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating thermohygrometer: ' . $e->getMessage(), [
                'name' => $this->name,
                'description' => $this->description,
                'exception' => $e->getTraceAsString()
            ]);
            $this->addError('name', 'An error occurred while saving the thermohygrometer.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;

        $this->validate();

        try {
            $thermohygrometer = Thermohygrometer::findOrFail($this->editingId);
            $thermohygrometer->update([
                'name' => $this->name,
                'description' => $this->description
            ]);

            $this->closeEditModal();
            $this->dispatch('$refresh');
            session()->flash('success', 'Thermohygrometer updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating thermohygrometer: ' . $e->getMessage(), [
                'name' => $this->name,
                'description' => $this->description,
                'editingId' => $this->editingId,
                'exception' => $e->getTraceAsString()
            ]);
            $this->addError('name', 'An error occurred while updating the thermohygrometer.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $thermohygrometer = Thermohygrometer::findOrFail($id);
            $thermohygrometer->delete();
            $this->dispatch('$refresh');
            session()->flash('success', 'Thermohygrometer deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the thermohygrometer.');
        }
    }

    public function updatingName()
    {
        $this->resetErrorBag('name');
    }

    public function updatingDescription()
    {
        $this->resetErrorBag('description');
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        return view('livewire.thermohygrometer-management', [
            'thermohygrometers' => Thermohygrometer::select('id', 'name', 'description')
                ->latest()
                ->paginate(10)
        ])->layout('layouts.app')->title('Thermohygrometer Management');
    }
}
