<?php

namespace App\Livewire\SampleChemicalSubmission\Components;

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
    public $showConfirmation = false;

    // Form fields
    public $category_id = '';
    public $material_id = '';
    public $reference_id = '';
    public $batch_lot = '';
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
        'batch_lot' => 'required|string|max:255',
        'submission_date' => 'required|date',
        'submission_time' => 'required',
        'notes' => 'required|string|max:1000',
    ];

    protected $messages = [
        'category_id.required' => 'Kategori wajib dipilih.',
        'category_id.exists' => 'Kategori yang dipilih tidak valid.',
        'material_id.required' => 'Material wajib dipilih.',
        'material_id.exists' => 'Material yang dipilih tidak valid.',
        'reference_id.required' => 'Reference wajib dipilih.',
        'reference_id.exists' => 'Reference yang dipilih tidak valid.',
        'batch_lot.required' => 'Batch/Lot number wajib diisi.',
        'batch_lot.string' => 'Batch/Lot number harus berupa teks.',
        'batch_lot.max' => 'Batch/Lot number maksimal 255 karakter.',
        'submission_date.required' => 'Tanggal submission wajib diisi.',
        'submission_date.date' => 'Tanggal submission tidak valid.',
        'submission_time.required' => 'Waktu submission wajib diisi.',
        'notes.required' => 'Catatan wajib diisi.',
        'notes.string' => 'Catatan harus berupa teks.',
        'notes.max' => 'Catatan maksimal 1000 karakter.',
    ];

    public function mount()
    {
        $this->categories = Category::where('type', 'chemical')->get();  // ✅ GANTI KE 'chemical'
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
        $this->batch_lot = '';
        $this->submission_date = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->submission_time = Carbon::now('Asia/Jakarta')->format('H:i');
        $this->notes = '';
        $this->materials = collect();
        $this->references = collect();
        $this->showConfirmation = false;
        $this->resetErrorBag();
    }

    public function validateBeforeConfirm()
    {
        try {
            $this->validate();

            // If validation passes, set property to show modal
            $this->showConfirmation = true;

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Dispatch event to scroll to first error
            $this->dispatch('scroll-to-error');
            throw $e;
        }
    }

    public function submit()
    {
        try {
            $this->validate();

            // Clear any previous scroll-to-error dispatch
            $this->dispatch('clear-scroll-error');

            // Get pending status ID
            $pendingStatus = Status::where('name', 'pending')->first();

            Sample::create([
                'sample_type' => 'chemical',
                'category_id' => $this->category_id,
                'material_id' => $this->material_id,
                'reference_id' => $this->reference_id,
                'batch_lot' => $this->batch_lot,
                'submission_time' => $this->submission_date . ' ' . Carbon::now('Asia/Jakarta')->format('H:i:s'),
                'entry_time' => Carbon::now('Asia/Jakarta'),
                'submitted_by' => auth()->id(),
                'status_id' => $pendingStatus ? $pendingStatus->id : null,
                'notes' => $this->notes,
            ]);

            session()->flash('message', 'Solder sample submitted successfully!');
            $this->hide();
            $this->dispatch('sampleCreated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Dispatch event to scroll to first error
            $this->dispatch('scroll-to-error');
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Error submitting sample: ' . $e->getMessage());
            \Log::error('Sample submission error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sample-chemical-submission.components.create-sample-form');  // ✅ GANTI VIEW PATH
    }
}
