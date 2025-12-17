<?php

namespace App\Livewire\SampleRawmatSubmission;

use App\Models\Specification as SpecificationsModels;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Specification extends Component
{
    use WithPagination;

    // Form data
    public $name = '';
    public $editingId = null;

    // Modal states
    public $isAddModalOpen = false;
    public $isEditModalOpen = false;

    // Loading states
    public $isSubmitting = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:specifications,name',
        ];

        // For edit, exclude current record from unique validation
        if ($this->editingId) {
            $rules['name'] = Rule::unique('specifications', 'name')->ignore($this->editingId);
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama specification wajib diisi.',
        'name.unique' => 'Nama specification sudah ada.',
    ];

    public function render()
    {
        $specifications = SpecificationsModels::select('id', 'name')
            ->withCount('referenceManytoMany')
            ->paginate(10);

        return view('livewire.sample-rawmat-submission.specification', [
            'specifications' => $specifications,
        ])->layout('layouts.app')->title('Specifications');
    }

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

    public function openEditModal($id)
    {
        $specification = SpecificationsModels::find($id);

        $this->resetForm();
        $this->editingId = $id;
        $this->name = $specification->name;
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
            SpecificationsModels::create([
                'name' => $this->name,
            ]);

            $this->closeAddModal();
            session()->flash('success', 'Specification berhasil dibuat.');
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat menyimpan specification.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;

        $this->validate();

        try {
            $specification = SpecificationsModels::find($this->editingId);
            $specification->update([
                'name' => $this->name,
            ]);

            $this->closeEditModal();
            session()->flash('success', 'Specification berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat memperbarui specification.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $specification = SpecificationsModels::findOrFail($id);

            // Check if specification is being used by any references
            if ($specification->referenceManytoMany()->count() > 0) {
                session()->flash('error', 'Specification tidak dapat dihapus karena masih digunakan oleh reference.');
                return;
            }

            $specification->delete();
            session()->flash('success', 'Specification berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus specification.');
        }
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }
}
