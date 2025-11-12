<?php

namespace App\Livewire;

use App\Models\Reference as ReferenceModel;
use App\Models\Specification;
use App\Models\RawMat;
use App\Enums\OperatorType;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Reference extends Component
{
    use WithPagination;

    public $rawmats;
    public $specifications;

    public function mount()
    {
        $this->rawmats = Rawmat::select('id', 'name')->get();
        $this->specifications = Specification::select('id', 'name')->get();
    }

    // Form data
    public $name = '';
    public $rawmat_id = '';
    public $rawmatSearch = '';
    public $showRawmatDropdown = false;
    public $selectedSpecifications = [];
    public $specificationValues = []; // Array to store values for each specification
    public $specificationMaxValues = []; // Array to store max values for range operator
    public $specificationRanges = []; // Array to store multiple range pairs for each specification
    public $specificationTextValues = []; // Array to store text values for should_be operator
    public $specificationOperators = []; // Array to store operators for each specification
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
            'rawmat_id' => 'required|exists:raw_mats,id',
            'selectedSpecifications' => 'array',
            'selectedSpecifications.*' => 'exists:specifications,id',
            'specificationValues' => 'array',
            'specificationOperators' => 'array',
        ];

        // Build name validation rules
        $nameRules = ['required', 'string', 'max:255'];

        // Add unique validation
        if (!empty($this->rawmat_id)) {
            $uniqueRule = Rule::unique('references', 'name')
                ->where('rawmat_id', $this->rawmat_id);

            if ($this->editingId) {
                $uniqueRule->ignore($this->editingId);
            }

            $nameRules[] = $uniqueRule;
        }

        $rules['name'] = $nameRules;

        // Rest of your validation rules...
        foreach ($this->selectedSpecifications as $specId) {
            $rules["specificationOperators.{$specId}"] = 'required|in:>=,<=,==,-,should_be,range';

            $operator = $this->specificationOperators[$specId] ?? '==';

            if ($operator === '-') {
                $rules["specificationRanges.{$specId}"] = 'required|array|min:1';
                $rules["specificationRanges.{$specId}.*.min"] = 'required|numeric';
                $rules["specificationRanges.{$specId}.*.max"] = 'required|numeric';
            } elseif ($operator === 'should_be') {
                $rules["specificationTextValues.{$specId}"] = 'required|string';
            } else {
                $rules["specificationValues.{$specId}"] = 'required|numeric';
            }
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Custom validation for range pairs
            foreach ($this->selectedSpecifications as $specId) {
                if (isset($this->specificationOperators[$specId]) && $this->specificationOperators[$specId] === '-') {
                    if (isset($this->specificationRanges[$specId])) {
                        foreach ($this->specificationRanges[$specId] as $index => $range) {
                            if (isset($range['min']) && isset($range['max'])) {
                                $min = (float) $range['min'];
                                $max = (float) $range['max'];
                                if ($max <= $min) {
                                    $validator->errors()->add(
                                        "specificationRanges.{$specId}.{$index}.max",
                                        'Max value must be greater than min value.'
                                    );
                                }
                            }
                        }
                    }
                }
            }
        });
    }

    protected $messages = [
        'name.required' => 'Nama reference wajib diisi.',
        'rawmat_id.required' => 'Raw material wajib dipilih.',
        'rawmat_id.exists' => 'Raw material yang dipilih tidak valid.',
        'selectedSpecifications.*.exists' => 'Spesifikasi yang dipilih tidak valid.',
        'specificationValues.*.required' => 'Nilai spesifikasi wajib diisi.',
        'specificationValues.*.numeric' => 'Nilai spesifikasi harus berupa angka.',
        'specificationOperators.*.required' => 'Operator wajib dipilih.',
        'specificationOperators.*.in' => 'Operator tidak valid.',
    ];

    public function render()
    {
        $references = ReferenceModel::with([
            'rawmat',
            'rawmat.category',
            'specificationsManytoMany'
        ])->paginate(10);

        // Group references by raw material name - use the items() method to get the collection
        $groupedReferences = collect();

        // Ensure $references->items() returns array/collection
        $items = is_array($references->items()) ? $references->items() : [];

        foreach ($items as $reference) {
            $rawmatName = $reference->rawmat->name;
            if (!$groupedReferences->has($rawmatName)) {
                $groupedReferences->put($rawmatName, collect());
            }
            $groupedReferences->get($rawmatName)->push($reference);
        }

        return view('livewire.reference', [
            'references' => $references,
            'groupedReferences' => $groupedReferences,
        ])->layout('layouts.app')->title('References');
    }

    public function openAddModal()
    {
        // Check if rawmats are available
        if ($this->rawmats->isEmpty()) {
            session()->flash('error', 'Tidak dapat menambah reference. Silakan tambahkan raw material terlebih dahulu.');
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

    public function openEditModal($id)
    {
        $reference = ReferenceModel::with('specificationsManytoMany')->find($id);

        $this->resetForm();
        $this->editingId = $id;
        $this->name = $reference->name;
        $this->rawmat_id = $reference->rawmat_id;
        $this->rawmatSearch = $reference->rawmat->name;

        // Load selected specifications and their values
        $this->selectedSpecifications = $reference->specificationsManytoMany->pluck('id')->toArray();

        // Initialize specification values and operators from pivot table
        foreach ($reference->specificationsManytoMany as $spec) {
            $this->specificationOperators[$spec->id] = $spec->pivot->operator ?? '==';

            if ($spec->pivot->operator === '-') {
                // Load single range from value and max_value columns
                $this->specificationRanges[$spec->id] = [
                    [
                        'min' => $spec->pivot->value ?? '',
                        'max' => $spec->pivot->max_value ?? ''
                    ]
                ];
            } else {
                $this->specificationValues[$spec->id] = $spec->pivot->value ?? '';
            }
        }

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
        $this->rawmat_id = '';
        $this->rawmatSearch = '';
        $this->showRawmatDropdown = false;
        $this->selectedSpecifications = [];
        $this->specificationValues = [];
        $this->specificationMaxValues = [];
        $this->specificationRanges = [];
        $this->specificationTextValues = [];
        $this->specificationOperators = [];
        $this->editingId = null;
        $this->isSubmitting = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->isSubmitting = true;

        // Debug: Log the current state before validation
        \Log::info('Store method called with data:', [
            'name' => $this->name,
            'rawmat_id' => $this->rawmat_id,
        ]);

        $this->validate();

        try {
            // Additional safety check before creating
            $existingReference = ReferenceModel::where('name', $this->name)
                ->where('rawmat_id', $this->rawmat_id)
                ->first();

            if ($existingReference) {
                \Log::error('Duplicate reference found during store:', [
                    'existing_id' => $existingReference->id,
                    'name' => $this->name,
                    'rawmat_id' => $this->rawmat_id
                ]);

                $this->addError('name', 'Reference dengan nama ini untuk raw material yang sama sudah ada.');
                return;
            }

            $reference = ReferenceModel::create([
                'name' => $this->name,
                'rawmat_id' => $this->rawmat_id,
            ]);

            // Sync specifications with values and operators
            if (!empty($this->selectedSpecifications)) {
                $syncData = [];
                foreach ($this->selectedSpecifications as $specId) {
                    $operator = $this->specificationOperators[$specId] ?? '==';

                    if ($operator === '-') {
                        // Get only the first range pair (single range support)
                        $ranges = $this->specificationRanges[$specId] ?? [];
                        $range = $ranges[0] ?? ['min' => '', 'max' => ''];

                        $syncData[$specId] = [
                            'value' => $range['min'] !== '' ? (float) $range['min'] : null,
                            'max_value' => $range['max'] !== '' ? (float) $range['max'] : null,
                            'text_value' => null,
                            'operator' => $operator
                        ];
                    } elseif ($operator === 'should_be') {
                        $syncData[$specId] = [
                            'value' => null,
                            'max_value' => null,
                            'text_value' => $this->specificationTextValues[$specId] ?? null,
                            'operator' => $operator
                        ];

                    } else {
                        $syncData[$specId] = [
                            'value' => $this->specificationValues[$specId] ?? null,
                            'max_value' => null,
                            'text_value' => null,
                            'operator' => $operator
                        ];
                    }
                }
                $reference->specificationsManytoMany()->sync($syncData);
            }

            $this->closeAddModal();
            session()->flash('success', 'Reference berhasil dibuat.');
        } catch (\Exception $e) {
            \Log::error('Error saving reference: ' . $e->getMessage());
            $this->addError('name', 'Terjadi kesalahan saat menyimpan reference: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;

        \Log::info('Update method called with data:', [
            'editingId' => $this->editingId,
            'name' => $this->name,
            'rawmat_id' => $this->rawmat_id,
        ]);

        $this->validate();

        try {
            // Additional safety check before updating
            $existingReference = ReferenceModel::where('name', $this->name)
                ->where('rawmat_id', $this->rawmat_id)
                ->where('id', '!=', $this->editingId)
                ->first();

            if ($existingReference) {
                \Log::error('Duplicate reference found during update:', [
                    'existing_id' => $existingReference->id,
                    'editing_id' => $this->editingId,
                    'name' => $this->name,
                    'rawmat_id' => $this->rawmat_id
                ]);

                $this->addError('name', 'Reference dengan nama ini untuk raw material yang sama sudah ada.');
                return;
            }

            $reference = ReferenceModel::find($this->editingId);
            $reference->update([
                'name' => $this->name,
                'rawmat_id' => $this->rawmat_id,
            ]);

            // Sync specifications with values and operators
            $syncData = [];
            foreach ($this->selectedSpecifications as $specId) {
                $operator = $this->specificationOperators[$specId] ?? '==';
                if ($operator === '-') {
                    // Get only the first range pair (single range support)
                    $ranges = $this->specificationRanges[$specId] ?? [];
                    $range = $ranges[0] ?? ['min' => '', 'max' => ''];

                    $syncData[$specId] = [
                        'value' => $range['min'] !== '' ? (float) $range['min'] : null,
                        'max_value' => $range['max'] !== '' ? (float) $range['max'] : null,
                        'operator' => $operator
                    ];
                } elseif ($operator === 'should_be') {
                    $syncData[$specId] = [
                        'value' => null,
                        'max_value' => null,
                        'text_value' => $this->specificationTextValues[$specId] ?? null,
                        'operator' => $operator
                    ];
                } else {
                    $syncData[$specId] = [
                        'value' => $this->specificationValues[$specId] ?? null,
                        'max_value' => null,
                        'operator' => $operator
                    ];
                }
            }
            $reference->specificationsManytoMany()->sync($syncData);

            $this->closeEditModal();
            session()->flash('success', 'Reference berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating reference: ' . $e->getMessage());
            $this->addError('name', 'Terjadi kesalahan saat memperbarui reference: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $reference = ReferenceModel::findOrFail($id);

            // The pivot table records will be automatically deleted due to cascadeOnDelete
            $reference->delete();

            session()->flash('success', 'Reference berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus reference');
        }
    }

    public function toggleSpecification($specificationId)
    {
        if (in_array($specificationId, $this->selectedSpecifications)) {
            // Remove from selected and clear its value and operator
            $this->selectedSpecifications = array_diff($this->selectedSpecifications, [$specificationId]);
            unset($this->specificationValues[$specificationId]);
            unset($this->specificationMaxValues[$specificationId]);
            unset($this->specificationRanges[$specificationId]);
            unset($this->specificationOperators[$specificationId]);
        } else {
            // Add to selected and initialize empty value and default operator
            $this->selectedSpecifications[] = $specificationId;
            $this->specificationValues[$specificationId] = '';
            $this->specificationMaxValues[$specificationId] = '';
            $this->specificationTextValues[$specificationId] = '';
            $this->specificationRanges[$specificationId] = [['min' => '', 'max' => '']];
            $this->specificationOperators[$specificationId] = '==';
        }
    }

    // Handle operator change and initialize range inputs automatically
    public function updatedSpecificationOperators($value, $key)
    {
        if ($value === '-') {
            // When switching to range operator, initialize with one empty range pair
            if (!isset($this->specificationRanges[$key]) || empty($this->specificationRanges[$key])) {
                $this->specificationRanges[$key] = [['min' => '', 'max' => '']];
            }
            // Clear regular value when switching to range
            unset($this->specificationValues[$key]);
            unset($this->specificationTextValues[$key]);
        } elseif ($value === 'should_be') {
            // When switching to should_be operator, initialize text value
            unset($this->specificationRanges[$key]);
            unset($this->specificationValues[$key]);
            if (!isset($this->specificationTextValues[$key])) {
                $this->specificationTextValues[$key] = '';
            }
        } else {
            // When switching to numeric operators, clear range and text data
            unset($this->specificationRanges[$key]);
            unset($this->specificationTextValues[$key]);
            // Initialize regular value
            if (!isset($this->specificationValues[$key])) {
                $this->specificationValues[$key] = '';
            }
        }
    }

    public function addRangeRow($specificationId)
    {
        if (!isset($this->specificationRanges[$specificationId])) {
            $this->specificationRanges[$specificationId] = [];
        }
        $this->specificationRanges[$specificationId][] = ['min' => '', 'max' => ''];
    }

    public function removeRangeRow($specificationId, $index)
    {
        if (isset($this->specificationRanges[$specificationId][$index])) {
            unset($this->specificationRanges[$specificationId][$index]);
            $this->specificationRanges[$specificationId] = array_values($this->specificationRanges[$specificationId]);

            // Ensure at least one range row exists
            if (empty($this->specificationRanges[$specificationId])) {
                $this->specificationRanges[$specificationId] = [['min' => '', 'max' => '']];
            }
        }
    }

    public function getFilteredRawmatsProperty()
    {
        if (empty($this->rawmatSearch)) {
            return $this->rawmats;
        }

        return $this->rawmats->filter(function ($rawmat) {
            return stripos($rawmat->name, $this->rawmatSearch) !== false;
        });
    }

    public function selectRawmat($id, $name)
    {
        $this->rawmat_id = $id;
        $this->rawmatSearch = $name;
        $this->showRawmatDropdown = false;

        // Validate name again when rawmat changes
        if (!empty($this->name)) {
            $this->validateOnly('name');
        }
    }

    public function updatedName()
    {
        if (!empty($this->rawmat_id) && !empty($this->name)) {
            $this->validateOnly('name');
        }
    }

    public function updatedRawmatSearch()
    {
        $this->showRawmatDropdown = !empty($this->rawmatSearch);
        if (empty($this->rawmatSearch)) {
            $this->rawmat_id = '';
        }
    }

    public function openRawmatDropdown()
    {
        $this->showRawmatDropdown = true;
    }

    public function closeRawmatDropdown()
    {
        $this->showRawmatDropdown = false;
    }
}
