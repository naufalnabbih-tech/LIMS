<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Validation\Rule;
class RawMatCategory extends Component
{
    use WithPagination;

    // Modal states
    public bool $isAddModalOpen = false;
    public bool $isEditModalOpen = false;

    // Form data
    public $name = '';
    public $editingId = null;

    // Loading states
    public $isSubmitting = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where('type', 'raw_material')
            ],
        ];

        // For edit, exclude current record from unique validation
        if ($this->editingId) {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where('type', 'raw_material')
                    ->ignore($this->editingId)
            ];
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.unique' => 'Nama kategori ini sudah ada.',
        'name.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
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

    public function openEditModal($id, $name)
    {
        $this->resetForm();
        $this->editingId = $id;
        $this->name = $name;
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
            Category::create([
                'type' => 'raw_material',
                'name' => $this->name
            ]);

            $this->closeAddModal();
            $this->resetPage();
            session()->flash('success', 'Kategori berhasil dibuat.');
        } catch (\Exception $e) {
            \Log::error('Error creating category: ' . $e->getMessage(), [
                'name' => $this->name,
                'exception' => $e->getTraceAsString()
            ]);
            $this->addError('name', 'Terjadi kesalahan saat menyimpan kategori.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;

        $this->validate();

        try {
            $category = Category::findOrFail($this->editingId);
            $category->update([
                'name' => $this->name
            ]);

            $this->closeEditModal();
            $this->dispatch('$refresh');
            session()->flash('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating category: ' . $e->getMessage(), [
                'name' => $this->name,
                'editingId' => $this->editingId,
                'exception' => $e->getTraceAsString()
            ]);
            $this->addError('name', 'Terjadi kesalahan saat memperbarui kategori.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            $this->dispatch('$refresh');
            session()->flash('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus kategori.');
        }
    }

    public function updatingName()
    {
        $this->resetErrorBag('name');
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }
    public function render()
    {
        return view('livewire.rawmat-category', [
            'categories' => Category::select('id', 'name', 'type')
                ->where('type', 'raw_material')
                ->latest()
                ->paginate(10)
        ])->layout('layouts.app')->title('Raw Material Categories');
    }
}
