<?php

namespace App\Livewire;

use App\Models\Reference;
use App\Models\Specification;
use App\Models\Material;
use App\Enums\OperatorType;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class SolderReference extends Component
{
    use WithPagination;

    public $solders;
    public $specifications;
    public $showMessage = false;

    public function mount()
    {
        $this->solders = Material::select('id', 'name')
            ->whereHas('category', function($q) {
                $q->where('type', 'solder');
            })
            ->get();
        $this->specifications = Specification::select('id', 'name')->get();
    }

    public $name = '';
    public $material_id = '';
    public $solderSearch = '';
    public $showSolderDropdown = false;
    public $selectedSpecifications = [];
    public $specificationValues = [];
    public $specificationMaxValues = [];
    public $specificationRanges = [];
    public $specificationOperators = [];
    public $editingId = null;

    public $isAddModalOpen = false;
    public $isEditModalOpen = false;

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

        $nameRules = ['required', 'string', 'max:255'];

        if (!empty($this->material_id)) {
            $uniqueRule = Rule::unique('references', 'name')
                ->where('material_id', $this->material_id);

            if ($this->editingId) {
                $uniqueRule->ignore($this->editingId);
            }

            $nameRules[] = $uniqueRule;
        }

        $rules['name'] = $nameRules;

        foreach ($this->selectedSpecifications as $specId) {
            $rules["specificationOperators.{$specId}"] = 'required|in:>=,<=,==,-,should_be,range';

            $operator = $this->specificationOperators[$specId] ?? '==';

            if ($operator === '-') {
                $rules["specificationRanges.{$specId}"] = 'required|array|min:1';
                $rules["specificationRanges.{$specId}.*.min"] = 'required|numeric';
                $rules["specificationRanges.{$specId}.*.max"] = 'required|numeric';
            } else {
                if (in_array($operator, ['>=', '<=', '=='])) {
                    $rules["specificationValues.{$specId}"] = 'required|numeric';
                } else {
                    $rules["specificationValues.{$specId}"] = 'required|string';
                }
            }
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
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
        'material_id.required' => 'Solder wajib dipilih.',
        'material_id.exists' => 'Solder yang dipilih tidak valid.',
        'selectedSpecifications.*.exists' => 'Spesifikasi yang dipilih tidak valid.',
        'specificationValues.*.required' => 'Nilai spesifikasi wajib diisi.',
        'specificationValues.*.numeric' => 'Nilai spesifikasi harus berupa angka.',
        'specificationOperators.*.required' => 'Operator wajib dipilih.',
        'specificationOperators.*.in' => 'Operator tidak valid.',
        'specificationTextValues.*.required' => 'Nilai teks spesifikasi wajib diisi.',
        'specificationTextValues.*.string' => 'Nilai teks spesifikasi harus berupa teks.',
        'specificationRanges.*.*.min.required' => 'Nilai minimum wajib diisi.',
        'specificationRanges.*.*.min.numeric' => 'Nilai minimum harus berupa angka.',
        'specificationRanges.*.*.max.required' => 'Nilai maksimum wajib diisi.',
        'specificationRanges.*.*.max.numeric' => 'Nilai maksimum harus berupa angka.',
    ];

    public function render()
    {
        $references = Reference::with([
            'material',
            'material.category',
            'specificationsManytoMany'
        ])
        ->whereHas('material.category', function($q) {
            $q->where('type', 'solder');
        })
        ->paginate(10);

        $groupedReferences = collect();
        foreach ($references->items() as $reference) {
            $solderName = $reference->material->name;
            if (!$groupedReferences->has($solderName)) {
                $groupedReferences->put($solderName, collect());
            }
            $groupedReferences->get($solderName)->push($reference);
        }

        return view('livewire.solder-reference', [
            'references' => $references,
            'groupedReferences' => $groupedReferences,
        ])->layout('layouts.app')->title('Solder References');
    }

    public function openAddModal()
    {
        if ($this->solders->isEmpty()) {
            session()->flash('error', 'Tidak dapat menambah reference. Silakan tambahkan solder terlebih dahulu.');
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
        $reference = Reference::with('specificationsManytoMany')->find($id);

        $this->resetForm();
        $this->editingId = $id;
        $this->name = $reference->name;
        $this->material_id = $reference->material_id;
        $this->solderSearch = $reference->material->name;

        $this->selectedSpecifications = $reference->specificationsManytoMany->pluck('id')->toArray();

        foreach ($reference->specificationsManytoMany as $spec) {
            $this->specificationOperators[$spec->id] = $spec->pivot->operator ?? '==';

            if ($spec->pivot->operator === '-') {
                $rangeData = json_decode($spec->pivot->value, true);
                $this->specificationRanges[$spec->id] = $rangeData ?: [['min' => '', 'max' => '']];
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
        $this->material_id = '';
        $this->solderSearch = '';
        $this->showSolderDropdown = false;
        $this->selectedSpecifications = [];
        $this->specificationValues = [];
        $this->specificationMaxValues = [];
        $this->specificationRanges = [];
        $this->specificationOperators = [];
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
            $existingReference = Reference::where('name', $this->name)
                ->where('material_id', $this->material_id)
                ->first();

            if ($existingReference) {
                $this->addError('name', 'Reference dengan nama ini untuk solder yang sama sudah ada.');
                return;
            }

            $reference = Reference::create([
                'name' => $this->name,
                'material_id' => $this->material_id,
            ]);

            if (!empty($this->selectedSpecifications)) {
                $syncData = [];
                foreach ($this->selectedSpecifications as $specId) {
                    $operator = $this->specificationOperators[$specId] ?? '==';

                    if ($operator === '-') {
                        $ranges = $this->specificationRanges[$specId] ?? [];
                        $cleanRanges = [];

                        foreach ($ranges as $range) {
                            if (
                                isset($range['min']) && isset($range['max']) &&
                                $range['min'] !== '' && $range['max'] !== ''
                            ) {
                                $cleanRanges[] = [
                                    'min' => (string) $range['min'],
                                    'max' => (string) $range['max']
                                ];
                            }
                        }

                        $syncData[$specId] = [
                            'value' => json_encode($cleanRanges),
                            'max_value' => null,
                            'operator' => $operator
                        ];
                    } else {
                        $syncData[$specId] = [
                            'value' => $this->specificationValues[$specId] ?? '',
                            'max_value' => null,
                            'operator' => $operator
                        ];
                    }
                }
                $reference->specificationsManytoMany()->sync($syncData);
            }

            $this->closeAddModal();
            session()->flash('success', 'Solder reference berhasil dibuat.');
            $this->showMessage = true;
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat menyimpan reference: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function update()
    {
        $this->isSubmitting = true;

        $this->validate();

        try {
            $existingReference = Reference::where('name', $this->name)
                ->where('material_id', $this->material_id)
                ->where('id', '!=', $this->editingId)
                ->first();

            if ($existingReference) {
                $this->addError('name', 'Reference dengan nama ini untuk solder yang sama sudah ada.');
                return;
            }

            $reference = Reference::find($this->editingId);
            $reference->update([
                'name' => $this->name,
                'material_id' => $this->material_id,
            ]);

            $syncData = [];
            foreach ($this->selectedSpecifications as $specId) {
                $operator = $this->specificationOperators[$specId] ?? '==';

                if ($operator === '-') {
                    $ranges = $this->specificationRanges[$specId] ?? [];
                    $cleanRanges = [];

                    foreach ($ranges as $range) {
                        if (
                            isset($range['min']) && isset($range['max']) &&
                            $range['min'] !== '' && $range['max'] !== ''
                        ) {
                            $cleanRanges[] = [
                                'min' => (string) $range['min'],
                                'max' => (string) $range['max']
                            ];
                        }
                    }

                    $syncData[$specId] = [
                        'value' => json_encode($cleanRanges),
                        'max_value' => null,
                        'operator' => $operator
                    ];
                } else {
                    $syncData[$specId] = [
                        'value' => $this->specificationValues[$specId] ?? '',
                        'max_value' => null,
                        'operator' => $operator
                    ];
                }
            }
            $reference->specificationsManytoMany()->sync($syncData);

            $this->closeEditModal();
            session()->flash('success', 'Solder reference berhasil diperbarui.');
            $this->showMessage = true;
        } catch (\Exception $e) {
            $this->addError('name', 'Terjadi kesalahan saat memperbarui reference: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function delete($id)
    {
        try {
            $reference = Reference::findOrFail($id);
            $reference->delete();
            session()->flash('success', 'Solder reference berhasil dihapus');
            $this->showMessage = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus reference');
            $this->showMessage = true;
        }
    }

    public function toggleSpecification($specificationId)
    {
        if (in_array($specificationId, $this->selectedSpecifications)) {
            $this->selectedSpecifications = array_diff($this->selectedSpecifications, [$specificationId]);
            unset($this->specificationValues[$specificationId]);
            unset($this->specificationMaxValues[$specificationId]);
            unset($this->specificationRanges[$specificationId]);
            unset($this->specificationOperators[$specificationId]);
        } else {
            $this->selectedSpecifications[] = $specificationId;
            $this->specificationValues[$specificationId] = '';
            $this->specificationMaxValues[$specificationId] = '';
            $this->specificationRanges[$specificationId] = [['min' => '', 'max' => '']];
            $this->specificationOperators[$specificationId] = '==';
        }
    }

    public function updatedSpecificationOperators($value, $key)
    {
        if ($value === '-') {
            if (!isset($this->specificationRanges[$key]) || empty($this->specificationRanges[$key])) {
                $this->specificationRanges[$key] = [['min' => '', 'max' => '']];
            }
            unset($this->specificationValues[$key]);
        } else {
            unset($this->specificationRanges[$key]);
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

            if (empty($this->specificationRanges[$specificationId])) {
                $this->specificationRanges[$specificationId] = [['min' => '', 'max' => '']];
            }
        }
    }

    public function getFilteredSoldersProperty()
    {
        if (empty($this->solderSearch)) {
            return $this->solders;
        }

        return $this->solders->filter(function ($solder) {
            return stripos($solder->name, $this->solderSearch) !== false;
        });
    }

    public function selectSolder($id, $name)
    {
        $this->material_id = $id;
        $this->solderSearch = $name;
        $this->showSolderDropdown = false;

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

    public function updatedSolderSearch()
    {
        $this->showSolderDropdown = !empty($this->solderSearch);
        if (empty($this->solderSearch)) {
            $this->material_id = '';
        }
    }

    public function openSolderDropdown()
    {
        $this->showSolderDropdown = true;
    }

    public function closeSolderDropdown()
    {
        $this->showSolderDropdown = false;
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }
}
