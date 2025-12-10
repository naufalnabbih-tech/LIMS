<?php

namespace App\Livewire;

use App\Models\Material;
use App\Models\Reference as ReferenceModel;
use App\Models\Specification;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Reference extends Component
{
    use WithPagination;

    public $materials;
    public $specifications;

    public function mount()
    {
        $this->materials = Material::select('id', 'name')->get();
        $this->specifications = Specification::select('id', 'name')->get();
    }

    // Form data
    public $name = '';
    public $material_id = '';
    public $materialSearch = '';
    public $showMaterialDropdown = false;
    public $selectedSpecifications = [];
    public $specificationValues = []; // Array to store values for each specification
    public $specificationMaxValues = []; // Array to store max values for range operator
    public $specificationRanges = []; // Array to store multiple range pairs for each specification
    public $specificationTextValues = []; // Array to store text values for should_be operator
    public $specificationOperators = []; // Array to store operators for each specification
    public $specificationUnits = []; // Array to store units for each specification
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
            'material_id' => 'required|exists:materials,id',
            'selectedSpecifications' => 'array',
            'selectedSpecifications.*' => 'exists:specifications,id',
            'specificationValues' => 'array',
            'specificationOperators' => 'array',

        ];

        // Build name validation rules
        $nameRules = ['required', 'string', 'max:255'];

        // Add unique validation
        if (!empty($this->material_id)) {
            $uniqueRule = Rule::unique('references', 'name')
                ->where('material_id', $this->material_id);

            if ($this->editingId) {
                $uniqueRule->ignore($this->editingId);
            }

            $nameRules[] = $uniqueRule;
        }

        $rules['name'] = $nameRules;

        // Rest of your validation rules...
        foreach ($this->selectedSpecifications as $specId) {
            $rules["specificationOperators.{$specId}"] = 'required|in:>=,<=,==,-,should_be,range';
            $rules["specificationUnits.{$specId}"] = 'required|string';

            $operator = $this->specificationOperators[$specId] ?? '==';

            if ($operator === '-') {
                $rules["specificationRanges.{$specId}"] = 'required|array|min:1';
                $rules["specificationRanges.{$specId}.*.min"] = 'required|numeric';

                // Add custom validation for max value with closure
                $rules["specificationRanges.{$specId}.*.max"] = [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) use ($specId) {
                        // Extract the index from attribute path: specificationRanges.1.0.max
                        preg_match('/specificationRanges\.\d+\.(\d+)\.max/', $attribute, $matches);
                        $index = $matches[1] ?? 0;

                        // Get the corresponding min value
                        $minValue = $this->specificationRanges[$specId][$index]['min'] ?? null;

                        \Log::info('Custom range validation:', [
                            'attribute' => $attribute,
                            'specId' => $specId,
                            'index' => $index,
                            'min' => $minValue,
                            'max' => $value,
                            'minIsSet' => isset($this->specificationRanges[$specId][$index]['min']),
                            'maxIsSet' => isset($this->specificationRanges[$specId][$index]['max'])
                        ]);

                        if ($minValue !== null && $minValue !== '' && $value !== null && $value !== '') {
                            $min = (float) $minValue;
                            $max = (float) $value;

                            if ($max <= $min) {
                                \Log::info('Range validation FAILED', ['min' => $min, 'max' => $max]);
                                $fail('Nilai maksimum harus lebih besar dari nilai minimum.');
                            }
                        }
                    }
                ];
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
                    // FIX: Tambahkan check is_array()
                    if (isset($this->specificationRanges[$specId]) && is_array($this->specificationRanges[$specId])) {
                        foreach ($this->specificationRanges[$specId] as $index => $range) {
                            if (isset($range['min']) && isset($range['max']) && $range['min'] !== '' && $range['max'] !== '') {
                                $min = (float) $range['min'];
                                $max = (float) $range['max'];

                                // Logging dihapus atau dikomentari agar tidak memenuhi log production
                                // \Log::info('Range validation check:', ...);

                                if ($max <= $min) {
                                    $validator->errors()->add(
                                        "specificationRanges.{$specId}.{$index}.max",
                                        'Nilai maksimum harus lebih besar dari nilai minimum.'
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
        'material_id.required' => 'Raw material wajib dipilih.',
        'material_id.exists' => 'Raw material yang dipilih tidak valid.',
        'selectedSpecifications.*.exists' => 'Spesifikasi yang dipilih tidak valid.',
        'specificationValues.*.required' => 'Nilai spesifikasi wajib diisi.',
        'specificationValues.*.numeric' => 'Nilai spesifikasi harus berupa angka.',
        'specificationOperators.*.required' => 'Operator wajib dipilih.',
        'specificationOperators.*.in' => 'Operator tidak valid.',
        'specificationTextValues.*.required' => 'Nilai spesifikasi wajib diisi.',
        'specificationTextValues.*.string' => 'Nilai  spesifikasi harus berupa teks.',
        'specificationRanges.*.*.min.required' => 'Nilai minimum wajib diisi.',
        'specificationRanges.*.*.min.numeric' => 'Nilai minimum harus berupa angka.',
        'specificationRanges.*.*.max.required' => 'Nilai maksimum wajib diisi.',
        'specificationRanges.*.*.max.numeric' => 'Nilai maksimum harus berupa angka.',
        'specificationUnits.*.required' => 'Satuan spesifikasi wajib diisi.',
    ];

    public function render()
    {
        $references = ReferenceModel::with([
            'material',
            'material.category',
            'specificationsManytoMany'
        ])->paginate(10);

        // Group references by raw material name - use the items() method to get the collection
        $groupedReferences = collect();

        $items = $references->items();
        if (!is_iterable($items)) {
            $items = [];
        }

        foreach ($items as $reference) {
            $materialName = $reference->material->name;
            if (!$groupedReferences->has($materialName)) {
                $groupedReferences->put($materialName, collect());
            }
            $groupedReferences->get($materialName)->push($reference);
        }

        return view('livewire.sample-rawmat-submission.components.reference', [
            'references' => $references,
            'groupedReferences' => $groupedReferences,
        ])->layout('layouts.app')->title('References');
    }

    public function openAddModal()
    {
        // Check if materials are available
        if ($this->materials->isEmpty()) {
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
        $this->material_id = $reference->material_id;
        $this->materialSearch = $reference->material->name;

        // Load selected specifications and their values
        $this->selectedSpecifications = $reference->specificationsManytoMany->pluck('id')->toArray();

        // Initialize specification values and operators from pivot table
        foreach ($reference->specificationsManytoMany as $spec) {
            $this->specificationOperators[$spec->id] = $spec->pivot->operator ?? '==';
            $this->specificationUnits[$spec->id] = $spec->pivot->unit ?? '';

            if ($spec->pivot->operator === '-') {
                // Load single range from value and max_value columns
                $this->specificationRanges[$spec->id] = [
                    [
                        'min' => $spec->pivot->value ?? '',
                        'max' => $spec->pivot->max_value ?? ''
                    ]
                ];
            } elseif ($spec->pivot->operator === 'should_be') {
                // Load text value for should_be operator
                $this->specificationTextValues[$spec->id] = $spec->pivot->text_value ?? '';
            } else {
                // Load numeric value for other operators (>=, <=, ==)
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
        $this->material_id = '';
        $this->materialSearch = '';
        $this->showMaterialDropdown = false;
        $this->selectedSpecifications = [];
        $this->specificationValues = [];
        $this->specificationMaxValues = [];
        $this->specificationRanges = [];
        $this->specificationTextValues = [];
        $this->specificationOperators = [];
        $this->specificationUnits = [];
        $this->editingId = null;
        $this->isSubmitting = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $this->isSubmitting = true;

        try {
            // Additional safety check before creating
            $existingReference = ReferenceModel::where('name', $this->name)
                ->where('material_id', $this->material_id)
                ->first();

            if ($existingReference) {
                \Log::error('Duplicate reference found during store:', [
                    'existing_id' => $existingReference->id,
                    'name' => $this->name,
                    'material_id' => $this->material_id
                ]);

                $this->addError('name', 'Reference dengan nama ini untuk raw material yang sama sudah ada.');
                return;
            }

            $reference = ReferenceModel::create([
                'name' => $this->name,
                'material_id' => $this->material_id,
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
                            'operator' => $operator,
                            'unit' => $this->specificationUnits[$specId] ?? null
                        ];
                    } elseif ($operator === 'should_be') {
                        $syncData[$specId] = [
                            'value' => null,
                            'max_value' => null,
                            'text_value' => $this->specificationTextValues[$specId] ?? null,
                            'operator' => $operator,
                            'unit' => $this->specificationUnits[$specId] ?? null
                        ];

                    } else {
                        $syncData[$specId] = [
                            'value' => $this->specificationValues[$specId] ?? null,
                            'max_value' => null,
                            'text_value' => null,
                            'operator' => $operator,
                            'unit' => $this->specificationUnits[$specId] ?? null
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
            'material_id' => $this->material_id,
        ]);

        $this->validate();

        try {
            // Additional safety check before updating
            $existingReference = ReferenceModel::where('name', $this->name)
                ->where('material_id', $this->material_id)
                ->where('id', '!=', $this->editingId)
                ->first();

            if ($existingReference) {
                \Log::error('Duplicate reference found during update:', [
                    'existing_id' => $existingReference->id,
                    'editing_id' => $this->editingId,
                    'name' => $this->name,
                    'material_id' => $this->material_id
                ]);

                $this->addError('name', 'Reference dengan nama ini untuk raw material yang sama sudah ada.');
                return;
            }

            $reference = ReferenceModel::find($this->editingId);
            $reference->update([
                'name' => $this->name,
                'material_id' => $this->material_id,
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
                        'text_value' => null,
                        'operator' => $operator,
                        'unit' => $this->specificationUnits[$specId] ?? null
                    ];
                } elseif ($operator === 'should_be') {
                    $syncData[$specId] = [
                        'value' => null,
                        'max_value' => null,
                        'text_value' => $this->specificationTextValues[$specId] ?? null,
                        'operator' => $operator,
                        'unit' => $this->specificationUnits[$specId] ?? null
                    ];
                } else {
                    $syncData[$specId] = [
                        'value' => $this->specificationValues[$specId] ?? null,
                        'max_value' => null,
                        'text_value' => null,
                        'operator' => $operator,
                        'unit' => $this->specificationUnits[$specId] ?? null
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
            // Remove from selected
            // FIX: Gunakan array_values() setelah array_diff untuk mereset index array
            $this->selectedSpecifications = array_values(array_diff($this->selectedSpecifications, [$specificationId]));

            // Clean up data
            unset($this->specificationValues[$specificationId]);
            unset($this->specificationMaxValues[$specificationId]);
            unset($this->specificationRanges[$specificationId]);
            unset($this->specificationOperators[$specificationId]);
            unset($this->specificationTextValues[$specificationId]); // Jangan lupa unset ini juga
            unset($this->specificationUnits[$specificationId]);
        } else {
            // Add to selected
            $this->selectedSpecifications[] = $specificationId;

            // Initialize values
            $this->specificationValues[$specificationId] = '';
            $this->specificationMaxValues[$specificationId] = '';
            $this->specificationTextValues[$specificationId] = '';
            $this->specificationRanges[$specificationId] = [['min' => '', 'max' => '']];
            $this->specificationOperators[$specificationId] = '==';
            $this->specificationUnits[$specificationId] = '';
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
        if (empty($this->materialSearch)) {
            return $this->materials;
        }

        return $this->materials->filter(function ($material) {
            return stripos($material->name, $this->materialSearch) !== false;
        });
    }

    // Alias for blade compatibility
    public function getFilteredMaterialsProperty()
    {
        return $this->getFilteredRawmatsProperty();
    }

    public function selectMaterial($id, $name)
    {
        $this->material_id = $id;
        $this->materialSearch = $name;
        $this->showMaterialDropdown = false;
        // Validate name again when material changes
        if (!empty($this->name)) {
            $this->validateOnly('name');
        }
    }

    public function updatedName()
    {
        if (!empty($this->material_id) && !empty($this->name)) {
            $this->validateOnly('name');
        }
    }

    public function updatedMaterialSearch()
    {
        $this->showMaterialDropdown = !empty($this->materialSearch);
        if (empty($this->materialSearch)) {
            $this->material_id = '';
        }
    }

    public function openMaterialDropdown()
    {
        $this->showMaterialDropdown = true;
    }

    public function closeMaterialDropdown()
    {
        $this->showMaterialDropdown = false;
    }
}
