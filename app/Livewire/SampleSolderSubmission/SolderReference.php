<?php

namespace App\Livewire\SampleSolderSubmission;

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
    public $specificationTextValues = [];
    public $specificationUnits = [];
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
            $rules["specificationUnits.{$specId}"] = 'required|string';

            $operator = $this->specificationOperators[$specId] ?? '==';

            if ($operator === '-') {
                $rules["specificationRanges.{$specId}"] = 'required|array|min:1';
                $rules["specificationRanges.{$specId}.*.min"] = 'required|numeric';
                $rules["specificationRanges.{$specId}.*.max"] = [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) use ($specId) {
                        // Extract index from attribute: specificationRanges.{specId}.{index}.max
                        preg_match('/specificationRanges\.\d+\.(\d+)\.max/', $attribute, $matches);
                        $index = $matches[1] ?? 0;

                        $minValue = $this->specificationRanges[$specId][$index]['min'] ?? null;

                        if ($minValue !== null && $minValue !== '' && $value !== null && $value !== '') {
                            $min = (float) $minValue;
                            $max = (float) $value;

                            if ($max <= $min) {
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
        'specificationUnits.*.required' => 'Satuan spesifikasi wajib diisi.',
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

        return view('livewire.sample-solder-submission.solder-reference', [
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
                $this->specificationTextValues[$spec->id] = $spec->pivot->text_value ?? '';
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
        $this->specificationTextValues = [];
        $this->specificationUnits = [];
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
            unset($this->specificationTextValues[$specificationId]);
            unset($this->specificationUnits[$specificationId]);
        } else {
            $this->selectedSpecifications[] = $specificationId;
            $this->specificationValues[$specificationId] = '';
            $this->specificationMaxValues[$specificationId] = '';
            $this->specificationRanges[$specificationId] = [['min' => '', 'max' => '']];
            $this->specificationOperators[$specificationId] = '==';
            $this->specificationUnits[$specificationId] = '';
        }
    }

    public function updatedSpecificationOperators($value, $key)
    {
        if ($value === '-') {
            if (!isset($this->specificationRanges[$key]) || empty($this->specificationRanges[$key])) {
                $this->specificationRanges[$key] = [['min' => '', 'max' => '']];
            }
            unset($this->specificationValues[$key]);
            unset($this->specificationTextValues[$key]);
        } elseif ($value === 'should_be') {
            unset($this->specificationRanges[$key]);
            unset($this->specificationValues[$key]);
            if (!isset($this->specificationTextValues[$key])) {
                $this->specificationTextValues[$key] = '';
            }
        } else {
            unset($this->specificationRanges[$key]);
            unset($this->specificationTextValues[$key]);
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
