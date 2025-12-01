<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;
use App\Models\Category;
use Illuminate\Validation\Rule;

class Solder extends Component
{
    use WithPagination;

    public $categories;

    public function mount()
    {
        // Optimized for 3G networks - load only essential fields
        $this->categories = Category::select('id', 'name')
            ->where('type', 'solder')
            ->get();
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
            'name' => 'required|string|max:255|unique:materials,name',
            'category_id' => 'required|exists:categories,id',
        ];

        // For edit, exclude current record from unique validation
        if ($this->editingId) {
            $rules['name'] = Rule::unique('materials', 'name')->ignore($this->editingId);
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Nama solder wajib diisi.',
        'category_id.required' => 'Kategori solder wajib diisi.',
        'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        'name.unique' => 'Nama solder ini sudah ada.',
    ];

    public function render()
    {
        return view('livewire.solder', [
            'solders' => Material::with('category')
                ->whereHas('category', function($q) {
                    $q->where('type', 'solder');
                })
                ->paginate(10),
        ])->layout('layouts.app')->title('Solders');
    }

    public function openAddModal()
    {
        // Check if categories are available
        if ($this->categories->isEmpty()) {
            session()->flash('error', 'Tidak dapat menambah solder. Silakan tambahkan kategori terlebih dahulu.');
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
            Material::create([
                'name' => $this->name,
                'category_id' => $this->category_id,
            ]);

            $this->closeAddModal();
            session()->flash('success', 'Solder berhasil dibuat.');
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat menyimpan solder.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;
        $this->validate();
        try {
            $solder = Material::find($this->editingId);
            $solder->update([
                'name' => $this->name,
                'category_id' => $this->category_id,
            ]);

            $this->closeEditModal();
            session()->flash('success', 'Solder berhasil diperbarui.');
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat memperbarui solder.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $solder = Material::findOrFail($id);
            $solder->delete();
            session()->flash('success', 'Solder berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus solder');
        }
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }
}
