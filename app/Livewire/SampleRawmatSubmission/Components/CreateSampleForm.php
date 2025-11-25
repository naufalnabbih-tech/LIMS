<?php

namespace App\Livewire\SampleRawmatSubmission\Components;

use App\Models\Sample;
use App\Models\Category;
use App\Models\Material;
use App\Models\Reference;
use App\Models\Status;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class CreateSampleForm extends Component
{
    use WithFileUploads;

    // Form visibility
    public $showForm = false;

    // Form fields
    public $category_id = '';
    public $material_id = '';
    public $reference_id = '';
    public $supplier = '';
    public $batch_lot = '';
    public $vehicle_container_number = '';
    public $has_coa = false;
    public $coa_file = null;
    public $submission_date = '';
    public $submission_time = '';
    public $notes = '';

    // Data collections
    public $categories = [];
    public $materials = [];
    public $references = [];

    protected $listeners = [
        'openCreateForm' => 'show',
    ];

    protected $rules = [
        'category_id' => 'required|exists:categories,id',
        'material_id' => 'required|exists:materials,id',
        'reference_id' => 'required|exists:references,id',
        'supplier' => 'required|string|max:255',
        'batch_lot' => 'required|string|max:255',
        'vehicle_container_number' => 'required|string|max:255',
        'has_coa' => 'boolean',
        'coa_file' => 'nullable|required_if:has_coa,true|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        'submission_date' => 'required|date',
        'submission_time' => 'required',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->categories = Category::where('type', 'raw_material')->get();
        $this->materials = collect();
        $this->references = collect();
        $this->submission_date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->submission_time = Carbon::now('Asia/Jakarta')->format('H:i');
    }

    public function updatedCategoryId($value)
    {
        $this->material_id = '';
        $this->reference_id = '';
        if ($value) {
            $this->materials = Material::where('category_id', $value)->get();
        } else {
            $this->materials = collect();
        }
        $this->references = collect();
    }

    public function updatedMaterialId($value)
    {
        $this->reference_id = '';
        if ($value) {
            $this->references = Reference::where('material_id', $value)->get();
        } else {
            $this->references = collect();
        }
    }

    public function show()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function hide()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->category_id = '';
        $this->material_id = '';
        $this->reference_id = '';
        $this->supplier = '';
        $this->batch_lot = '';
        $this->vehicle_container_number = '';
        $this->has_coa = false;
        $this->coa_file = null;
        $this->submission_date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->submission_time = Carbon::now('Asia/Jakarta')->format('H:i');
        $this->notes = '';
        $this->rawMaterials = collect();
        $this->references = collect();
        $this->resetErrorBag();
    }

    public function submit()
    {
        try {
            $this->validate();

            $coaFilePath = null;
            if ($this->has_coa && $this->coa_file) {
                $coaFilePath = $this->coa_file->store('coa-files', 'public');
            }

            // Get pending status ID
            $pendingStatus = Status::where('name', 'pending')->first();

            Sample::create([
                'category_id' => $this->category_id,
                'material_id' => $this->material_id,
                'reference_id' => $this->reference_id,
                'supplier' => $this->supplier,
                'batch_lot' => $this->batch_lot,
                'vehicle_container_number' => $this->vehicle_container_number,
                'has_coa' => $this->has_coa,
                'coa_file_path' => $coaFilePath,
                'submission_time' => $this->submission_date . ' ' . Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'entry_time' => Carbon::now('Asia/Jakarta'),
                'submitted_by' => auth()->id(),
                'status_id' => $pendingStatus ? $pendingStatus->id : null,
                'notes' => $this->notes,
            ]);

            session()->flash('message', 'Raw material sample submitted successfully!');
            $this->hide();
            $this->dispatch('sampleCreated');
        } catch (\Exception $e) {
            session()->flash('error', 'Error submitting sample: ' . $e->getMessage());
            \Log::error('Sample submission error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.create-sample-form');
    }
}
