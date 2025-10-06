<?php

namespace App\Livewire;

use App\Models\InstrumentCondition;
use App\Models\Instrument;
use App\Models\User;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class InstrumentConditionManagement extends Component
{
    use WithPagination;

    public $shift = '';
    public $operator_name = '';
    public $operator_id = null;
    public $operators = []; // Array to store multiple operators
    public $time = '';
    public $date = '';
    public $instrumentConditions = [];
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
        
        // Add validation for each instrument condition
        foreach ($this->instrumentConditions as $instrumentId => $data) {
            if (!empty($data['condition'])) {
                $rules["instrumentConditions.{$instrumentId}.condition"] = 'required|in:good,damaged';
                if ($data['condition'] === 'damaged') {
                    $rules["instrumentConditions.{$instrumentId}.description"] = 'required|string|max:1000';
                } else {
                    $rules["instrumentConditions.{$instrumentId}.description"] = 'nullable|string|max:1000';
                }
            }
        }
        
        return $rules;
    }

    protected $messages = [
        'shift.required' => 'Please select a shift.',
        'operator_name.required' => 'Operator name is required.',
        'time.required' => 'Time is required.',
        'date.required' => 'Date is required.',
        'instrumentConditions.*.condition.required' => 'Please select condition for all instruments.',
        'instrumentConditions.*.condition.in' => 'Invalid condition selection.',
        'instrumentConditions.*.description.required_if' => 'Description is required when instrument condition is damaged.',
        'instrumentConditions.*.description.max' => 'Description cannot exceed 1000 characters.',
    ];


    public function updatingSearch()
    {
        $this->resetPage();
    }

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

    public function resetForm()
    {
        $this->reset(['shift', 'time', 'date', 'instrumentConditions', 'selectedConditionId', 'isEditing', 'isViewing', 'validationErrors']);
        
        // Reset operators - reinitialize based on current user
        $this->initializeOperatorData();
        
        $this->resetValidation();
        $this->initializeInstrumentConditions();
    }

    public function initializeInstrumentConditions()
    {
        $instruments = Instrument::orderBy('name')->get();
        $this->instrumentConditions = [];
        
        foreach ($instruments as $instrument) {
            $this->instrumentConditions[$instrument->id] = [
                'instrument_id' => $instrument->id,
                'instrument_name' => $instrument->name,
                'condition' => '',
                'description' => ''
            ];
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->setCurrentDateTime();
        $this->initializeInstrumentConditions();
        $this->showModal = true;
        $this->isEditing = false;
        $this->isViewing = false;
    }

    private function setCurrentDateTime()
    {
        $now = Carbon::now('Asia/Jakarta');
        $this->time = $now->format('H:i');
        $this->date = $now->format('Y-m-d');
        $this->shift = InstrumentCondition::getCurrentShift($now);
    }

    public function openViewModal($conditionId)
    {
        $condition = InstrumentCondition::with('instrument')->findOrFail($conditionId);
        $this->selectedConditionId = $condition->id;
        $this->shift = $condition->shift;
        $this->operator_name = $condition->operator_name;
        $this->time = $condition->time->format('H:i');
        $this->date = $condition->date->format('Y-m-d');
        
        // Load all instrument conditions for this condition entry
        $allConditions = InstrumentCondition::where('shift', $condition->shift)
            ->where('operator_name', $condition->operator_name)
            ->where('time', $condition->time)
            ->where('date', $condition->date)
            ->with('instrument')
            ->get();
            
        $this->instrumentConditions = [];
        foreach ($allConditions as $cond) {
            $this->instrumentConditions[$cond->instrument_id] = [
                'instrument_id' => $cond->instrument_id,
                'instrument_name' => $cond->instrument->name,
                'condition' => $cond->condition,
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
            $errors[] = 'Please select a shift';
        }
        if (empty(trim($this->operator_name))) {
            $errors[] = 'Please enter operator name';
        }
        if (empty($this->time)) {
            $errors[] = 'Please enter time';
        }
        if (empty($this->date)) {
            $errors[] = 'Please enter date';
        }
        
        // Check if all instruments have conditions selected
        $instruments = Instrument::orderBy('name')->get();
        foreach ($instruments as $instrument) {
            if (!isset($this->instrumentConditions[$instrument->id]) || 
                empty($this->instrumentConditions[$instrument->id]['condition'])) {
                $errors[] = "Please select condition for {$instrument->name}";
            } else {
                // Check if damaged instruments have descriptions
                if ($this->instrumentConditions[$instrument->id]['condition'] === 'damaged' &&
                    empty(trim($this->instrumentConditions[$instrument->id]['description']))) {
                    $errors[] = "Please describe damage for {$instrument->name}";
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
        // Check if all instruments have conditions selected
        $missingConditions = [];
        $instruments = Instrument::orderBy('name')->get();
        
        foreach ($instruments as $instrument) {
            if (!isset($this->instrumentConditions[$instrument->id]) || 
                empty($this->instrumentConditions[$instrument->id]['condition'])) {
                $missingConditions[] = $instrument->name;
            }
        }
        
        if (!empty($missingConditions)) {
            session()->flash('error', 'Please select conditions for all instruments: ' . implode(', ', $missingConditions));
            return;
        }

        $this->validate();

        try {
            // Create condition entries for all instruments
            foreach ($this->instrumentConditions as $instrumentData) {
                if (!empty($instrumentData['condition'])) {
                    InstrumentCondition::create([
                        'instrument_id' => $instrumentData['instrument_id'],
                        'shift' => $this->shift,
                        'operator_name' => $this->operator_name,
                        'condition' => $instrumentData['condition'],
                        'description' => $instrumentData['description'],
                        'time' => $this->time,
                        'date' => $this->date,
                    ]);
                }
            }

            session()->flash('success', 'Instrument conditions created successfully for all instruments.');
            $this->closeModal();
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            \Log::error('Error creating instrument conditions: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while saving instrument conditions.');
        }
    }

    public function updatedTime()
    {
        if ($this->time && !$this->isViewing) {
            $jakartaTime = Carbon::createFromFormat('H:i', $this->time, 'Asia/Jakarta');
            $this->shift = InstrumentCondition::getCurrentShift($jakartaTime);
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

    public function updatedInstrumentConditions($value, $key)
    {
        // Reset description if condition is good
        if (str_ends_with($key, '.condition')) {
            $instrumentId = explode('.', $key)[0];
            if ($this->instrumentConditions[$instrumentId]['condition'] === 'good') {
                $this->instrumentConditions[$instrumentId]['description'] = '';
            }
        }
        
        // Clear validation errors when instrument conditions are updated
        $this->validationErrors = [];
        
        // Reset validation errors
        $this->resetErrorBag("instrumentConditions.{$key}");
    }

    public function delete($conditionId)
    {
        try {
            // Delete all conditions for this specific condition entry group
            $condition = InstrumentCondition::findOrFail($conditionId);
            
            // Delete all instrument conditions with same shift, operator, time, and date
            InstrumentCondition::where('shift', $condition->shift)
                ->where('operator_name', $condition->operator_name)
                ->where('time', $condition->time)
                ->where('date', $condition->date)
                ->delete();
                
            session()->flash('success', 'Instrument condition entry deleted successfully.');
            $this->dispatch('$refresh');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the condition entry.');
        }
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        // Group conditions by shift, operator, time, date to show unique condition entries
        $conditions = InstrumentCondition::select('shift', 'operator_name', 'time', 'date', 'created_at')
            ->selectRaw('MIN(id) as id') // Get the first ID for each group
            ->where(function($query) {
                $query->where('shift', 'like', '%' . $this->search . '%')
                      ->orWhere('operator_name', 'like', '%' . $this->search . '%')
                      ->orWhere('date', 'like', '%' . $this->search . '%');
            })
            ->groupBy(['shift', 'operator_name', 'time', 'date', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shiftOptions = InstrumentCondition::getShiftOptions();
        $conditionOptions = InstrumentCondition::getConditionOptions();
        $instruments = Instrument::orderBy('name')->get();

        return view('livewire.instrument-condition-management', [
            'conditions' => $conditions,
            'shiftOptions' => $shiftOptions,
            'conditionOptions' => $conditionOptions,
            'instruments' => $instruments
        ])->layout('layouts.app')->title('Instrument Condition Management');
    }
}