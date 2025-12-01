<?php

namespace App\Livewire;

use App\Models\ThermohygrometerCondition;
use App\Models\Thermohygrometer;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class ThermohygrometerConditionManagement extends Component
{
    use WithPagination;

    public $shift = '';
    public $operator_name = '';
    public $operator_id = null;
    public $operators = []; // Array to store multiple operators
    public $time = '';
    public $date = '';
    public $thermohygrometerConditions = [];
    public $isCurrentUserOperator = false;
    public $availableOperators = [];
    
    #[Url(as: 'q')]
    public $search = '';
    
    public $selectedConditionId = null;
    public $showModal = false;
    public $isEditing = false;
    public $isViewing = false;
    public $validationErrors = [];
    
    protected $queryString = ['search' => ['except' => '']];

    public function mount()
    {
        $this->initializeOperatorData();
    }

    public function initializeOperatorData()
    {
        $currentUser = auth()->user();
        
        // Check if current user has operator role
        $this->isCurrentUserOperator = $currentUser->role && 
            (strtolower($currentUser->role->name) === 'operator' || 
             strtolower($currentUser->role->name) === 'user'); // assuming 'user' role can also be operator
             
        if ($this->isCurrentUserOperator) {
            // Add current user as first operator
            $this->operators = [
                [
                    'id' => $currentUser->id,
                    'name' => $currentUser->name,
                    'is_current_user' => true
                ]
            ];
            $this->updateOperatorNames();
        } else {
            // Initialize with one empty operator slot
            $this->operators = [
                [
                    'id' => null,
                    'name' => '',
                    'is_current_user' => false
                ]
            ];
        }
        
        // Get all users with operator role for dropdown
        $operatorRole = Role::where('name', 'operator')
                           ->orWhere('name', 'user') // include user role as potential operators
                           ->first();
        
        if ($operatorRole) {
            $this->availableOperators = User::where('role_id', $operatorRole->id)
                                           ->orWhereHas('role', function($q) {
                                               $q->whereIn('name', ['operator', 'user']);
                                           })
                                           ->orderBy('name')
                                           ->get();
        } else {
            // Fallback: get all users if no specific operator role exists
            $this->availableOperators = User::orderBy('name')->get();
        }
    }

    protected function rules()
    {
        $rules = [
            'shift' => 'required|string',
            'time' => 'required',
            'date' => 'required|date',
            'operator_name' => 'required|string|max:500', // Increased for multiple names
        ];
        
        // Validate operators array
        $rules['operators'] = 'required|array|min:1';
        foreach ($this->operators as $index => $operator) {
            if (!($operator['is_current_user'] ?? false)) {
                $rules["operators.{$index}.id"] = 'required|exists:users,id';
            }
        }
        
        // Add validation for each thermohygrometer condition
        foreach ($this->thermohygrometerConditions as $thermohygrometerId => $data) {
            if (!empty($data['condition'])) {
                $rules["thermohygrometerConditions.{$thermohygrometerId}.condition"] = 'required|in:good,damaged';
                if ($data['condition'] === 'good') {
                    $rules["thermohygrometerConditions.{$thermohygrometerId}.temperature"] = 'required|numeric|min:-50|max:100';
                    $rules["thermohygrometerConditions.{$thermohygrometerId}.humidity"] = 'required|numeric|min:0|max:100';
                    $rules["thermohygrometerConditions.{$thermohygrometerId}.description"] = 'nullable|string|max:1000';
                } elseif ($data['condition'] === 'damaged') {
                    $rules["thermohygrometerConditions.{$thermohygrometerId}.description"] = 'required|string|max:1000';
                    $rules["thermohygrometerConditions.{$thermohygrometerId}.temperature"] = 'nullable|numeric|min:-50|max:100';
                    $rules["thermohygrometerConditions.{$thermohygrometerId}.humidity"] = 'nullable|numeric|min:0|max:100';
                }
            }
        }
        
        return $rules;
    }

    protected $messages = [
        'shift.required' => 'Silakan pilih shift.',
        'operator_name.required' => 'Nama operator wajib diisi.',
        'time.required' => 'Waktu wajib diisi.',
        'date.required' => 'Tanggal wajib diisi.',
        'thermohygrometerConditions.*.condition.required' => 'Silakan pilih kondisi untuk semua thermohygrometer.',
        'thermohygrometerConditions.*.condition.in' => 'Pilihan kondisi tidak valid.',
        'thermohygrometerConditions.*.temperature.required' => 'Temperatur wajib diisi ketika kondisi baik.',
        'thermohygrometerConditions.*.temperature.numeric' => 'Temperatur harus berupa angka.',
        'thermohygrometerConditions.*.temperature.min' => 'Temperatur minimal -50°C.',
        'thermohygrometerConditions.*.temperature.max' => 'Temperatur maksimal 100°C.',
        'thermohygrometerConditions.*.humidity.required' => 'Kelembaban wajib diisi ketika kondisi baik.',
        'thermohygrometerConditions.*.humidity.numeric' => 'Kelembaban harus berupa angka.',
        'thermohygrometerConditions.*.humidity.min' => 'Kelembaban minimal 0%.',
        'thermohygrometerConditions.*.humidity.max' => 'Kelembaban maksimal 100%.',
        'thermohygrometerConditions.*.description.required' => 'Deskripsi wajib diisi ketika kondisi thermohygrometer rusak.',
        'thermohygrometerConditions.*.description.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['shift', 'time', 'date', 'thermohygrometerConditions', 'selectedConditionId', 'isEditing', 'isViewing', 'validationErrors']);
        
        // Reset operators - reinitialize based on current user
        $this->initializeOperatorData();
        
        $this->resetValidation();
        $this->initializeThermohygrometerConditions();
    }

    public function initializeThermohygrometerConditions()
    {
        $thermohygrometers = Thermohygrometer::orderBy('name')->get();
        $this->thermohygrometerConditions = [];
        
        foreach ($thermohygrometers as $thermohygrometer) {
            $this->thermohygrometerConditions[$thermohygrometer->id] = [
                'thermohygrometer_id' => $thermohygrometer->id,
                'thermohygrometer_name' => $thermohygrometer->name,
                'condition' => '',
                'temperature' => '',
                'humidity' => '',
                'description' => ''
            ];
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->setCurrentDateTime();
        $this->initializeThermohygrometerConditions();
        $this->showModal = true;
        $this->isEditing = false;
        $this->isViewing = false;
    }

    private function setCurrentDateTime()
    {
        $now = Carbon::now('Asia/Jakarta');
        $this->time = $now->format('H:i');
        $this->date = $now->format('Y-m-d');
        $this->shift = ThermohygrometerCondition::getCurrentShift($now);
    }

    public function openViewModal($conditionId)
    {
        $condition = ThermohygrometerCondition::with('thermohygrometer')->findOrFail($conditionId);
        $this->selectedConditionId = $condition->id;
        $this->shift = $condition->shift;
        $this->operator_name = $condition->operator_name;
        $this->time = $condition->time->format('H:i');
        $this->date = $condition->date->format('Y-m-d');
        
        // Load all thermohygrometer conditions for this condition entry
        $allConditions = ThermohygrometerCondition::where('shift', $condition->shift)
            ->where('operator_name', $condition->operator_name)
            ->where('time', $condition->time)
            ->where('date', $condition->date)
            ->with('thermohygrometer')
            ->get();
            
        $this->thermohygrometerConditions = [];
        foreach ($allConditions as $cond) {
            $this->thermohygrometerConditions[$cond->thermohygrometer_id] = [
                'thermohygrometer_id' => $cond->thermohygrometer_id,
                'thermohygrometer_name' => $cond->thermohygrometer->name,
                'condition' => $cond->condition,
                'temperature' => $cond->temperature,
                'humidity' => $cond->humidity,
                'description' => $cond->description
            ];
        }
        
        $this->showModal = true;
        $this->isEditing = false;
        $this->isViewing = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function validateAndSave()
    {
        $this->validationErrors = [];
        $errors = [];

        // Check basic form fields
        if (empty($this->shift)) {
            $errors[] = 'Silakan pilih shift';
        }
        if (empty(trim($this->operator_name))) {
            $errors[] = 'Silakan masukkan nama operator';
        }
        if (empty($this->time)) {
            $errors[] = 'Silakan masukkan waktu';
        }
        if (empty($this->date)) {
            $errors[] = 'Silakan masukkan tanggal';
        }

        // Check if all thermohygrometers have conditions selected
        $thermohygrometers = Thermohygrometer::orderBy('name')->get();
        foreach ($thermohygrometers as $thermohygrometer) {
            if (!isset($this->thermohygrometerConditions[$thermohygrometer->id]) ||
                empty($this->thermohygrometerConditions[$thermohygrometer->id]['condition'])) {
                $errors[] = "Silakan pilih kondisi untuk {$thermohygrometer->name}";
            } else {
                // Check condition-specific requirements
                if ($this->thermohygrometerConditions[$thermohygrometer->id]['condition'] === 'good') {
                    if (empty($this->thermohygrometerConditions[$thermohygrometer->id]['temperature'])) {
                        $errors[] = "Silakan masukkan temperatur untuk {$thermohygrometer->name}";
                    }
                    if (empty($this->thermohygrometerConditions[$thermohygrometer->id]['humidity'])) {
                        $errors[] = "Silakan masukkan kelembaban untuk {$thermohygrometer->name}";
                    }
                } elseif ($this->thermohygrometerConditions[$thermohygrometer->id]['condition'] === 'damaged' &&
                    empty(trim($this->thermohygrometerConditions[$thermohygrometer->id]['description']))) {
                    $errors[] = "Silakan deskripsikan kerusakan untuk {$thermohygrometer->name}";
                }
            }
        }

        if (!empty($errors)) {
            $this->validationErrors = $errors;
            return;
        }

        // If validation passes, proceed with saving
        $this->save();
    }

    public function save()
    {
        // Check if all thermohygrometers have conditions selected
        $missingConditions = [];
        $thermohygrometers = Thermohygrometer::orderBy('name')->get();
        
        foreach ($thermohygrometers as $thermohygrometer) {
            if (!isset($this->thermohygrometerConditions[$thermohygrometer->id]) || 
                empty($this->thermohygrometerConditions[$thermohygrometer->id]['condition'])) {
                $missingConditions[] = $thermohygrometer->name;
            }
        }
        
        if (!empty($missingConditions)) {
            session()->flash('error', 'Silakan pilih kondisi untuk semua thermohygrometer: ' . implode(', ', $missingConditions));
            return;
        }

        $this->validate();

        try {
            // Create condition entries for all thermohygrometers
            foreach ($this->thermohygrometerConditions as $thermohygrometerData) {
                if (!empty($thermohygrometerData['condition'])) {
                    ThermohygrometerCondition::create([
                        'thermohygrometer_id' => $thermohygrometerData['thermohygrometer_id'],
                        'shift' => $this->shift,
                        'operator_name' => $this->operator_name,
                        'condition' => $thermohygrometerData['condition'],
                        'temperature' => !empty($thermohygrometerData['temperature']) ? $thermohygrometerData['temperature'] : null,
                        'humidity' => !empty($thermohygrometerData['humidity']) ? $thermohygrometerData['humidity'] : null,
                        'description' => $thermohygrometerData['description'],
                        'time' => $this->time,
                        'date' => $this->date,
                    ]);
                }
            }

            session()->flash('success', 'Kondisi thermohygrometer berhasil disimpan untuk semua perangkat.');
            $this->closeModal();
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            \Log::error('Error creating thermohygrometer conditions: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyimpan kondisi thermohygrometer.');
        }
    }

    public function updatedTime()
    {
        if ($this->time && !$this->isViewing) {
            $jakartaTime = Carbon::createFromFormat('H:i', $this->time, 'Asia/Jakarta');
            $this->shift = ThermohygrometerCondition::getCurrentShift($jakartaTime);
        }
    }

    public function updated($propertyName)
    {
        // Clear validation errors when any form field is updated
        if (in_array($propertyName, ['shift', 'operator_name', 'time', 'date'])) {
            $this->validationErrors = [];
        }
    }

    public function addOperator()
    {
        $this->operators[] = [
            'id' => null,
            'name' => '',
            'is_current_user' => false
        ];
    }

    public function removeOperator($index)
    {
        if (count($this->operators) > 1 && !($this->operators[$index]['is_current_user'] ?? false)) {
            unset($this->operators[$index]);
            $this->operators = array_values($this->operators); // Re-index array
            $this->updateOperatorNames();
        }
    }

    public function updatedOperators($value, $key)
    {
        // Handle when an operator ID is selected
        if (strpos($key, '.id') !== false) {
            $index = explode('.', $key)[0];
            if ($this->operators[$index]['id']) {
                $operator = User::find($this->operators[$index]['id']);
                if ($operator) {
                    $this->operators[$index]['name'] = $operator->name;
                }
            } else {
                $this->operators[$index]['name'] = '';
            }
            $this->updateOperatorNames();
        }
        $this->validationErrors = [];
    }

    public function updateOperatorNames()
    {
        $operatorNames = [];
        foreach ($this->operators as $operator) {
            if (!empty($operator['name'])) {
                $operatorNames[] = $operator['name'];
            }
        }
        $this->operator_name = implode(', ', $operatorNames);
    }

    public function updatedOperatorId()
    {
        if ($this->operator_id && !$this->isCurrentUserOperator) {
            $operator = User::find($this->operator_id);
            if ($operator) {
                $this->operator_name = $operator->name;
            }
        }
        $this->validationErrors = [];
    }

    public function updatedThermohygrometerConditions($value, $key)
    {
        // Reset fields based on condition selection
        if (str_ends_with($key, '.condition')) {
            $thermohygrometerId = explode('.', $key)[0];
            $condition = $this->thermohygrometerConditions[$thermohygrometerId]['condition'];
            
            if ($condition === 'good') {
                // Clear description when condition is good
                $this->thermohygrometerConditions[$thermohygrometerId]['description'] = '';
                // Initialize temperature and humidity if not set
                if (empty($this->thermohygrometerConditions[$thermohygrometerId]['temperature'])) {
                    $this->thermohygrometerConditions[$thermohygrometerId]['temperature'] = '';
                }
                if (empty($this->thermohygrometerConditions[$thermohygrometerId]['humidity'])) {
                    $this->thermohygrometerConditions[$thermohygrometerId]['humidity'] = '';
                }
            } elseif ($condition === 'damaged') {
                // Clear temperature and humidity when condition is damaged
                $this->thermohygrometerConditions[$thermohygrometerId]['temperature'] = '';
                $this->thermohygrometerConditions[$thermohygrometerId]['humidity'] = '';
                // Initialize description if not set
                if (empty($this->thermohygrometerConditions[$thermohygrometerId]['description'])) {
                    $this->thermohygrometerConditions[$thermohygrometerId]['description'] = '';
                }
            } else {
                // Clear all fields when no condition selected
                $this->thermohygrometerConditions[$thermohygrometerId]['temperature'] = '';
                $this->thermohygrometerConditions[$thermohygrometerId]['humidity'] = '';
                $this->thermohygrometerConditions[$thermohygrometerId]['description'] = '';
            }
            
            // Force a UI refresh
            $this->dispatch('$refresh');
        }
        
        // Clear validation errors when thermohygrometer conditions are updated
        $this->validationErrors = [];
        
        // Reset validation errors
        $this->resetErrorBag("thermohygrometerConditions.{$key}");
    }

    public function delete($conditionId)
    {
        try {
            // Delete all conditions for this specific condition entry group
            $condition = ThermohygrometerCondition::findOrFail($conditionId);
            
            // Delete all thermohygrometer conditions with same shift, operator, time, and date
            ThermohygrometerCondition::where('shift', $condition->shift)
                ->where('operator_name', $condition->operator_name)
                ->where('time', $condition->time)
                ->where('date', $condition->date)
                ->delete();
                
            session()->flash('success', 'Data kondisi thermohygrometer berhasil dihapus.');
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat menghapus data kondisi.');
        }
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        // Group conditions by shift, operator, time, date to show unique condition entries
        $conditions = ThermohygrometerCondition::select('shift', 'operator_name', 'time', 'date', 'created_at')
            ->selectRaw('MIN(id) as id') // Get the first ID for each group
            ->where(function($query) {
                $query->where('shift', 'like', '%' . $this->search . '%')
                      ->orWhere('operator_name', 'like', '%' . $this->search . '%')
                      ->orWhere('date', 'like', '%' . $this->search . '%');
            })
            ->groupBy(['shift', 'operator_name', 'time', 'date', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shiftOptions = ThermohygrometerCondition::getShiftOptions();
        $conditionOptions = ThermohygrometerCondition::getConditionOptions();
        $thermohygrometers = Thermohygrometer::orderBy('name')->get();

        return view('livewire.thermohygrometer-condition-management', [
            'conditions' => $conditions,
            'shiftOptions' => $shiftOptions,
            'conditionOptions' => $conditionOptions,
            'thermohygrometers' => $thermohygrometers
        ])->layout('layouts.app')->title('Thermohygrometer Condition Management');
    }
}
