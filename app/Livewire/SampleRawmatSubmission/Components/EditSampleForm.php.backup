<?php

namespace App\Livewire\SampleRawmatSubmission\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\RawMaterialSample;
use App\Models\RawMatCategory;
use App\Models\RawMat;
use App\Models\Reference;
use Illuminate\Support\Facades\Storage;

class EditSampleForm extends Component
{
    use WithFileUploads;

    public $sample;
    public $show = false;

    // Edit form properties
    public $edit_category_id = '';
    public $edit_raw_mat_id = '';
    public $edit_reference_id = '';
    public $edit_supplier = '';
    public $edit_batch_lot = '';
    public $edit_vehicle_container_number = '';
    public $edit_has_coa = false;
    public $edit_coa_file = null;
    public $edit_submission_date = '';
    public $edit_submission_time = '';
    public $edit_notes = '';

    public $categories = [];
    public $editRawMaterials = [];
    public $editReferences = [];

    protected $listeners = [
        'editSample' => 'open',
        'closeEditSampleForm' => 'close'
    ];

    protected $rules = [
        'edit_category_id' => 'required|exists:raw_mat_categories,id',
        'edit_raw_mat_id' => 'required|exists:raw_mats,id',
        'edit_reference_id' => 'required|exists:references,id',
        'edit_supplier' => 'required|string|max:255',
        'edit_batch_lot' => 'required|string|max:255',
        'edit_vehicle_container_number' => 'required|string|max:255',
        'edit_has_coa' => 'boolean',
        'edit_coa_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        'edit_submission_date' => 'required|date',
        'edit_submission_time' => 'required',
        'edit_notes' => 'nullable|string|max:1000',
    ];

    /**
     * Open the edit form modal
     *
     * @param int $sampleId
     * @return void
     */
    public function open($sampleId)
    {
        $this->sample = RawMaterialSample::with([
            'category',
            'rawMaterial',
            'reference'
        ])->find($sampleId);

        if ($this->sample) {
            $this->loadCategories();
            $this->loadFormData();
            $this->show = true;
        }
    }

    /**
     * Load categories for dropdown
     *
     * @return void
     */
    private function loadCategories()
    {
        $this->categories = RawMatCategory::orderBy('name')->get();
    }

    /**
     * Load existing sample data into form fields
     *
     * @return void
     */
    private function loadFormData()
    {
        $this->edit_category_id = $this->sample->category_id;
        $this->edit_raw_mat_id = $this->sample->raw_mat_id;
        $this->edit_reference_id = $this->sample->reference_id;
        $this->edit_supplier = $this->sample->supplier;
        $this->edit_batch_lot = $this->sample->batch_lot;
        $this->edit_vehicle_container_number = $this->sample->vehicle_container_number;
        $this->edit_has_coa = $this->sample->has_coa;
        $this->edit_submission_date = $this->sample->submission_time->format('Y-m-d');
        $this->edit_submission_time = $this->sample->submission_time->format('H:i');
        $this->edit_notes = $this->sample->notes;

        // Load raw materials for selected category
        if ($this->edit_category_id) {
            $this->editRawMaterials = RawMat::where('category_id', $this->edit_category_id)
                ->orderBy('name')
                ->get();
        }

        // Load references for selected raw material
        if ($this->edit_raw_mat_id) {
            $this->editReferences = Reference::where('rawmat_id', $this->edit_raw_mat_id)
                ->orderBy('name')
                ->get();
        }
    }

    /**
     * Update raw materials when category changes
     *
     * @return void
     */
    public function updatedEditCategoryId($value)
    {
        $this->editRawMaterials = RawMat::where('category_id', $value)
            ->orderBy('name')
            ->get();

        // Reset dependent fields
        $this->edit_raw_mat_id = '';
        $this->edit_reference_id = '';
        $this->editReferences = [];
    }

    /**
     * Update references when raw material changes
     *
     * @return void
     */
    public function updatedEditRawMatId($value)
    {
        $this->editReferences = Reference::where('rawmat_id', $value)
            ->orderBy('name')
            ->get();

        // Reset reference field
        $this->edit_reference_id = '';
    }

    /**
     * Close the edit form modal
     *
     * @return void
     */
    public function close()
    {
        $this->show = false;
        $this->sample = null;
        $this->reset([
            'edit_category_id',
            'edit_raw_mat_id',
            'edit_reference_id',
            'edit_supplier',
            'edit_batch_lot',
            'edit_vehicle_container_number',
            'edit_has_coa',
            'edit_coa_file',
            'edit_submission_date',
            'edit_submission_time',
            'edit_notes',
            'editRawMaterials',
            'editReferences'
        ]);
        $this->resetValidation();
    }

    /**
     * Update the sample
     *
     * @return void
     */
    public function updateSample()
    {
        $this->validate();

        try {
            $submissionDateTime = $this->edit_submission_date . ' ' . $this->edit_submission_time;

            $this->sample->update([
                'category_id' => $this->edit_category_id,
                'raw_mat_id' => $this->edit_raw_mat_id,
                'reference_id' => $this->edit_reference_id,
                'supplier' => $this->edit_supplier,
                'batch_lot' => $this->edit_batch_lot,
                'vehicle_container_number' => $this->edit_vehicle_container_number,
                'has_coa' => $this->edit_has_coa,
                'submission_time' => $submissionDateTime,
                'notes' => $this->edit_notes,
            ]);

            // Handle CoA file upload
            if ($this->edit_coa_file) {
                // Delete old file if exists
                if ($this->sample->coa_file_path) {
                    Storage::disk('public')->delete($this->sample->coa_file_path);
                }

                // Store new file
                $path = $this->edit_coa_file->store('coa-files', 'public');
                $this->sample->update(['coa_file_path' => $path]);
            }

            session()->flash('message', 'Sample updated successfully.');

            $this->dispatch('sampleUpdated');
            $this->close();

        } catch (\Exception $e) {
            session()->flash('error', 'Error updating sample: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sample-rawmat-submission.components.edit-sample-form');
    }
}
