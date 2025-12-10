<?php

namespace App\Livewire;

use App\Models\CoaDocumentFormat;
use Livewire\Component;
use Livewire\WithPagination;

class CoaDocumentFormatManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $modalMode = 'create';

    public $formatId;
    public $name = '';
    public $prefix = 'TI/COA';
    public $year_month = '';
    public $middle_part = 'MT';
    public $suffix = 'S0';
    public $is_active = true;
    public $description = '';
    public $customFields = [];

    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'prefix' => 'required|string|max:50',
        'year_month' => 'required|numeric|digits_between:1,10',
        'middle_part' => 'required|string|max:50',
        'suffix' => 'required|string|max:20',
        'description' => 'nullable|string',
    ];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $format = CoaDocumentFormat::findOrFail($id);

        $this->formatId = $format->id;
        $this->name = $format->name;
        $this->prefix = $format->prefix;
        $this->year_month = $format->year_month;
        $this->middle_part = $format->middle_part;
        $this->suffix = $format->suffix;
        $this->is_active = $format->is_active;
        $this->description = $format->description;
        $this->customFields = $format->custom_fields ?? [];

        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->modalMode === 'create') {
            CoaDocumentFormat::create([
                'name' => $this->name,
                'prefix' => $this->prefix,
                'year_month' => $this->year_month,
                'middle_part' => $this->middle_part,
                'suffix' => $this->suffix,
                'is_active' => $this->is_active,
                'description' => $this->description,
                'custom_fields' => !empty($this->customFields) ? $this->customFields : null,
            ]);

            session()->flash('message', 'Format dokumen berhasil ditambahkan!');
        } else {
            $format = CoaDocumentFormat::findOrFail($this->formatId);
            $format->update([
                'name' => $this->name,
                'prefix' => $this->prefix,
                'year_month' => $this->year_month,
                'middle_part' => $this->middle_part,
                'suffix' => $this->suffix,
                'is_active' => $this->is_active,
                'description' => $this->description,
                'custom_fields' => !empty($this->customFields) ? $this->customFields : null,
            ]);

            session()->flash('message', 'Format dokumen berhasil diupdate!');
        }

        $this->closeModal();
    }

    public function toggleActive($id)
    {
        $format = CoaDocumentFormat::findOrFail($id);
        $format->update(['is_active' => !$format->is_active]);

        session()->flash('message', 'Status format berhasil diubah!');
    }

    public function delete($id)
    {
        $format = CoaDocumentFormat::findOrFail($id);

        $format->delete();
        session()->flash('message', 'Format dokumen berhasil dihapus!');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->formatId = null;
        $this->name = '';
        $this->prefix = 'TI/COA';
        $this->year_month = '';
        $this->middle_part = 'MT';
        $this->suffix = 'S0';
        $this->is_active = true;
        $this->description = '';
        $this->customFields = [];
        $this->resetValidation();
    }

    public function addCustomField()
    {
        $this->customFields[] = [
            'key' => '',
            'label' => '',
            'type' => 'text',
            'required' => false,
            'options' => '', // For select type
        ];
    }

    public function removeCustomField($index)
    {
        unset($this->customFields[$index]);
        $this->customFields = array_values($this->customFields);
    }

    public function render()
    {
        $formats = CoaDocumentFormat::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('prefix', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.coa-document-format-management', [
            'formats' => $formats
        ]);
    }
}
