<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Validation\Rule;
class Rawmat extends Component
{
    use WithPagination;

    public $categories;

    public function mount()
    {
        // Optimized for 3G networks - load only essential fields
        $this->categories = Category::select('id', 'name')
            ->where('type', 'raw_material')
            ->get();
    }


    // Form data
    public $name = '';
    public $code = '';
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
            'category_id' => 'required|exists:categories,id',
        ];

        // For edit, exclude current record from unique validation
        if ($this->editingId) {
            $rules['name'] = ['required', 'string', 'max:255', Rule::unique('materials', 'name')->ignore($this->editingId)];
            $rules['code'] = ['required', 'string', 'max:50', Rule::unique('materials', 'code')->ignore($this->editingId)];
        } else {
            $rules['name'] = 'required|string|max:255|unique:materials,name';
            $rules['code'] = 'required|string|max:50|unique:materials,code';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama material wajib diisi.',
        'name.unique' => 'Nama material ini sudah ada.',
        'code.required' => 'Internal code wajib diisi.',
        'code.unique' => 'Internal code ini sudah digunakan.',
        'category_id.required' => 'Kategori material wajib diisi.',
        'category_id.exists' => 'Kategori yang dipilih tidak valid.',
    ];

    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.material', [
            'rawmat' => Material::with('category')
                ->whereHas('category', function($q) {
                    $q->where('type', 'raw_material');
                })
                ->paginate(10),
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

    public function openEditModal($id, $name, $code, $category_id)
    {
        $this->editingId = $id;
        $this->name = $name;
        $this->code = $code;
        $this->category_id = (string) $category_id; // Ensure it's a string to match option values
        $this->isSubmitting = false;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isEditModalOpen = true;

        // Force Livewire to re-render with new values
        $this->dispatch('$refresh');
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->code = '';
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
            Material::create([
                'name' => $this->name,
                'code' => $this->code,
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
            $material = Material::find($this->editingId);
            $material->update([
                'name' => $this->name,
                'code' => $this->code,
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
            $material = Material::findOrFail($id);
            $material->delete();
            session()->flash('success', 'Raw Material berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus raw material');
        }
    }
}
