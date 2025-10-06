<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RawMat as RawMatModel;
use App\Models\RawMatCategory;
use Illuminate\Validation\Rule;
class Rawmat extends Component
{
    use WithPagination;

    public $categories;

    public function mount()
    {
        // Optimized for 3G networks - load only essential fields
        $this->categories = RawMatCategory::select('id', 'name')->get();
    }


    // Form data
    public $name = '';
    public $category_id = '';

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
            'name' => 'required|string|max:255|unique:raw_mats,name',

            'category_id' => 'required|exists:raw_mat_categories,id', // Added validation for category
        ];

        // For edit, exclude current record from unique validation
        if ($this->editingId) {
            $rules['name'] = Rule::unique('raw_mats', 'name')->ignore($this->editingId);

        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama material wajib diisi.',
        'category_id.required' => 'Kategori material wajib diisi.',
        'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        'name.unique' => 'Nama material ini sudah ada.',
    ];

    public function render()
    {
        return view('livewire.rawmat', [
            'rawmat' => RawMatModel::with('category')->paginate(10),
        ])->layout('layouts.app')->title('Raw Materials');
    }

    public function openAddModal()
    {
        // Check if categories are available
        if ($this->categories->isEmpty()) {
            session()->flash('error', 'Tidak dapat menambah raw material. Silakan tambahkan kategori terlebih dahulu.');
            return;
        }

        $this->resetForm();
        $this->isAddModalOpen = true;
    }

    public function closeAddModal()
    {
        $this->isAddModalOpen = false;
        $this->resetForm();
    }

    public function openEditModal($id, $name, $category_id)
    {
        $this->resetForm();
        $this->editingId = $id;
        $this->name = $name;
        $this->category_id = $category_id;
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
        $this->category_id = '';
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
            RawMatModel::create([
                'name' => $this->name,
                'category_id' => $this->category_id,

            ]);

            $this->closeAddModal();
            session()->flash('success', 'Material berhasil dibuat.');
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat menyimpan material.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;
        $this->validate();
        try {
            $material = RawMatModel::find($this->editingId);
            $material->update([
                'name' => $this->name,
                'category_id' => $this->category_id,

            ]);

            $this->closeEditModal();
            session()->flash('success', 'Material berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat memperbarui material.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $material = RawMatModel::findOrFail($id);
            $material->delete();
            session()->flash('success', 'Raw Material berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus raw material');
        }
    }
}
