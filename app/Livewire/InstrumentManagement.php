<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Instrument;
use Illuminate\Validation\Rule;

class InstrumentManagement extends Component
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
            'name' => 'required|string|max:255|unique:instruments,name',
            'description' => 'nullable|string|max:1000',
        ];

        // For edit, exclude current record from unique validation
        if ($this->editingId) {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('instruments', 'name')->ignore($this->editingId)
            ];
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Instrument name is required.',
        'name.unique' => 'This instrument name already exists.',
        'name.max' => 'Instrument name cannot exceed 255 characters.',
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
            Instrument::create([
                'name' => $this->name,
                'description' => $this->description
            ]);

            $this->closeAddModal();
            $this->resetPage();
            session()->flash('success', 'Instrument created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating instrument: ' . $e->getMessage(), [
                'name' => $this->name,
                'description' => $this->description,
                'exception' => $e->getTraceAsString()
            ]);
            $this->addError('name', 'An error occurred while saving the instrument.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;

        $this->validate();

        try {
            $instrument = Instrument::findOrFail($this->editingId);
            $instrument->update([
                'name' => $this->name,
                'description' => $this->description
            ]);

            $this->closeEditModal();
            $this->dispatch('$refresh');
            session()->flash('success', 'Instrument updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating instrument: ' . $e->getMessage(), [
                'name' => $this->name,
                'description' => $this->description,
                'editingId' => $this->editingId,
                'exception' => $e->getTraceAsString()
            ]);
            $this->addError('name', 'An error occurred while updating the instrument.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $instrument = Instrument::findOrFail($id);
            $instrument->delete();
            $this->dispatch('$refresh');
            session()->flash('success', 'Instrument deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the instrument.');
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
        return view('livewire.instrument-management', [
            'instruments' => Instrument::select('id', 'name', 'description')
                ->latest()
                ->paginate(10)
        ])->layout('layouts.app')->title('Instrument Management');
    }
}
