<?php

namespace App\Livewire;


use App\Models\Sample;
use App\Models\Category;
use App\Models\Material;
use App\Models\Reference;
use App\Models\SampleHandover;
use App\Models\Status;
use App\Models\User;
use App\Models\CoA;
use App\Models\CoaDocumentFormat;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SampleSolderSubmission extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $searchBatchLot = '';

    // CoA Form Properties
    public $showCoAModal = false;
    public $coaData = [];
    public $coaDocumentNumber = '';
    public $coaFullNumber = '';
    public $coaNetWeight = '';
    public $coaPoNo = '';
    public $coaNotes = '';
    public $currentSampleId = null;
    public $coaFormatId = null;
    public $availableFormats = [];
    public $customFieldValues = [];

    protected $listeners = [
        'sampleUpdated' => '$refresh',
        'sampleCreated' => '$refresh',
        'analysisStarted' => '$refresh',
        'printSampleLabel' => 'handlePrintLabel',
        'handOverSubmitted' => '$refresh',
        'takeOverSubmitted' => '$refresh',
        'openCoAForm' => 'openCoAForm'
    ];

    public function updatingSearchBatchLot()
    {
        // Reset to first page when searching
        $this->resetPage();
    }

    // Action Methods - now dispatch events to child components
    public function viewDetails($sampleId)
    {
        $this->dispatch('openSampleDetails', sampleId: $sampleId);
    }

    public function editSample($sampleId)
    {
        $this->dispatch('editSample', sampleId: $sampleId);
    }

    public function handlePrintLabel($sampleId)
    {
        $sample = Sample::with([
            'category',
            'material',
            'reference',
            'submittedBy',
            'status'
        ])->findOrFail($sampleId);

        try {
            // Prepare label data
            $labelData = [
                'sample_id' => $sample->id,
                'material_name' => $sample->material->name ?? 'N/A',
                'category_name' => $sample->category->name ?? 'N/A',
                'supplier' => $sample->supplier,
                'batch_lot' => $sample->batch_lot,
                'submission_date' => $sample->submission_time->format('Y-m-d H:i'),
                'reference' => $sample->reference->name ?? 'N/A',
                'vehicle_container' => $sample->vehicle_container_number,
                'status' => $sample->status ? $sample->status->display_name : 'N/A',
                'submitted_by' => $sample->submittedBy->name ?? 'N/A',
            ];

            // Call JavaScript function directly
            $this->js('printSampleLabel(' . json_encode($labelData) . ')');

        } catch (\Exception $e) {
            session()->flash('error', 'Error preparing sample label: ' . $e->getMessage());
        }
    }

    // Analysis form methods - now dispatches to AnalysisForm component
    public function openAnalysisForm($sampleId)
    {
        $this->dispatch('openAnalysisForm', sampleId: $sampleId);
    }

    public function startAnalysis($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);
        $inProgressStatus = Status::where('name', 'in_progress')->first();

        $sample->update([
            'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
            'analysis_started_at' => Carbon::now('Asia/Jakarta'),
            'primary_analyst_id' => auth()->id()
        ]);

        session()->flash('message', "Analysis started for sample");

        return redirect()->route('analysis-page', ['sampleId' => $sampleId]);
    }

    public function continueAnalysis($sampleId)
    {
        return redirect()->route('analysis-page', ['sampleId' => $sampleId]);
    }

    public function completeAnalysis($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);
        $analysisCompletedStatus = Status::where('name', 'analysis_completed')->first();

        $sample->update([
            'status_id' => $analysisCompletedStatus ? $analysisCompletedStatus->id : null,
            'analysis_completed_at' => Carbon::now('Asia/Jakarta')
        ]);
        session()->flash('message', "Analysis completed for sample");
    }

    public function reviewResults($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);

        $currentStatusName = $sample->status ? $sample->status->name : '';

        if ($currentStatusName === 'approved') {
            return;
        }

        $reviewStatus = Status::where('name', 'reviewed')->first();

        $updateData = [
            'reviewed_at' => Carbon::now('Asia/Jakarta'),
            'reviewed_by' => auth()->id(),
            'status_id' => $reviewStatus ? $reviewStatus->id : null
        ];

        $sample->update($updateData);
    }

    public function approveSample($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);
        $approvedStatus = Status::where('name', 'approved')->first();

        $updateData = [
            'status_id' => $approvedStatus ? $approvedStatus->id : null,
            'approved_at' => Carbon::now('Asia/Jakarta'),
            'approved_by' => auth()->id()
        ];

        // If sample was never reviewed, automatically set reviewed timestamp to maintain history
        if (!$sample->reviewed_at) {
            $updateData['reviewed_at'] = Carbon::now('Asia/Jakarta');
            $updateData['reviewed_by'] = auth()->id();
        }

        $sample->update($updateData);
        session()->flash('message', "Sample has been approved");
    }

    public function deleteSample($sampleId)
    {
        $sample = Sample::with('status')->findOrFail($sampleId);

        // Delete associated CoA file if exists
        if ($sample->coa_file_path) {
            \Storage::disk('public')->delete($sample->coa_file_path);
        }

        $sample->delete();
        session()->flash('message', "Sample has been deleted successfully");
    }

    // Hand Over Methods
    public function openHandOverForm($sampleId)
    {
        $this->dispatch('openHandOverForm', sampleId: $sampleId);
    }

    public function openTakeOverForm($sampleId)
    {
        $this->dispatch('openTakeOverForm', sampleId: $sampleId);
    }

    // Take Over Methods
    public function takeSample($handoverId)
    {
        $handover = SampleHandover::with(['sample', 'toAnalyst'])->findOrFail($handoverId);

        if($handover->to_analyst_id != auth()->id()){
            session()->flash('error', 'You are not authorized to take this sample.');
            return;
        }

        if(!$handover->isPending()){
            session()->flash('error', 'This sample has already been taken over.');
            return;
        }

        $inProgressStatus = Status::where('name', 'in_progress')->first();

        // For Update Sample
        $handover->sample->update([
            'status_id' => $inProgressStatus ? $inProgressStatus->id : null,
            'primary_analyst_id' => auth()->id()
        ]);

        // For Update Handover
        $handover->update([
            'status' => 'accepted',
            'taken_at' => Carbon::now('Asia/Jakarta'),
            'taken_by' => auth()->id(),
        ]);

        session()->flash('message', 'Sample taken successfully. You are now the assigned analyst.');
        $this->dispatch('takeOverSubmitted');
    }

    // CoA Methods
    public function openCoAForm($sampleId)
    {
        $sample = Sample::with('material', 'category', 'reference')->findOrFail($sampleId);

        // Load active formats
        $formats = CoaDocumentFormat::where('is_active', true)->get();

        if ($formats->isEmpty()) {
            session()->flash('error', 'Format dokumen CoA tidak tersedia. Silakan buat format terlebih dahulu.');
            return;
        }

        $this->availableFormats = $formats->toArray();
        $this->coaFormatId = $this->coaFormatId ?? $formats->first()->id;

        $selectedFormat = $formats->firstWhere('id', $this->coaFormatId) ?? $formats->first();
        $this->applyFormat($selectedFormat);

        // Initialize custom field values
        $this->initializeCustomFields($selectedFormat);

        // Pre-populate data from sample
        $this->coaData = [
            'sample_id' => $sample->id,
            'batch_lot' => $sample->batch_lot,
            'material' => $sample->material?->name,
            'reference' => $sample->reference?->name,
            'submission_date' => $sample->submission_time?->format('d-m-Y'),
            'inspection_date' => now()->format('d F Y'),
        ];

        $this->currentSampleId = $sampleId;
        $this->showCoAModal = true;
    }

    protected function applyFormat($format)
    {
        if (is_array($format)) {
            $format = CoaDocumentFormat::find($format['id'] ?? null);
        }

        if (!$format) {
            return;
        }

        $this->coaDocumentNumber = $format->generateDocumentNumber();
        $this->coaFullNumber = $format->generateFullNumber();
        $this->initializeCustomFields($format);
    }

    protected function initializeCustomFields($format)
    {
        if (is_array($format)) {
            $format = CoaDocumentFormat::find($format['id'] ?? null);
        }

        $this->customFieldValues = [];

        if ($format && $format->custom_fields) {
            foreach ($format->custom_fields as $field) {
                $this->customFieldValues[$field['key']] = '';
            }
        }
    }

    public function updatedCoaFormatId($value)
    {
        $format = collect($this->availableFormats)->firstWhere('id', (int) $value);
        if ($format) {
            $this->applyFormat($format);
        }
    }

    public function closeCoAModal()
    {
        $this->resetCoAForm();
        $this->showCoAModal = false;
    }

    public function resetCoAForm()
    {
        $this->coaData = [];
        $this->coaDocumentNumber = '';
        $this->coaFullNumber = '';
        $this->coaNetWeight = '';
        $this->coaPoNo = '';
        $this->coaNotes = '';
        $this->currentSampleId = null;
        $this->coaFormatId = null;
        $this->availableFormats = [];
        $this->customFieldValues = [];
    }

    public function createCoA()
    {
        // Validate
        // For draft status, allow duplicate document numbers
        // Unique constraint only applies when changing status to approved/printed
        $this->validate([
            'coaFormatId' => 'required|exists:coa_document_formats,id',
            'coaDocumentNumber' => 'required|string',
            'coaNetWeight' => 'nullable|string',
            'coaPoNo' => 'nullable|string',
        ]);

        // Prepare data with custom fields definition
        $dataToSave = array_merge($this->coaData, $this->customFieldValues);

        // Include custom fields definition from format
        $format = CoaDocumentFormat::find($this->coaFormatId);
        if ($format && $format->custom_fields) {
            $dataToSave['_custom_fields_definition'] = $format->custom_fields;
        }

        // Create CoA
        CoA::create([
            'document_number' => $this->coaDocumentNumber,
            'format_id' => $this->coaFormatId,
            'sample_id' => $this->currentSampleId,
            'sample_type' => 'solder',
            'net_weight' => $this->coaNetWeight,
            'po_no' => $this->coaPoNo,
            'status' => 'draft',
            'notes' => $this->coaNotes,
            'data' => $dataToSave,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Certificate of Analysis created successfully!');
        $this->closeCoAModal();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            // Toggle sort direction if same column is clicked
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // New column, default to desc (most recent first)
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }
    }

    public function render()
    {
        $query = Sample::with(['category', 'material', 'reference', 'submittedBy', 'status'])
            ->where('sample_type', 'solder')
            ->where(function($query) {
                // Show all samples that are NOT in_progress
                $query->whereHas('status', function($q) {
                    $q->where('name', '!=', 'in_progress');
                })
                // OR show in_progress samples only if user is primary or secondary analyst
                ->orWhere(function($q) {
                    $q->whereHas('status', function($q2) {
                        $q2->where('name', 'in_progress');
                    })
                    ->where(function($q2) {
                        $q2->where('primary_analyst_id', auth()->id())
                          ->orWhere('secondary_analyst_id', auth()->id());
                    });
                });
            });

        // Apply search filter for batch/lot
        if (!empty($this->searchBatchLot)) {
            $query->where('batch_lot', 'like', '%' . $this->searchBatchLot . '%');
        }

        // Apply sorting
        if ($this->sortBy === 'status') {
            // Sort by status relationship
            $query->join('statuses', 'samples.status_id', '=', 'statuses.id')
                  ->select('samples.*', 'statuses.display_name as status_name')
                  ->orderBy('statuses.display_name', $this->sortDirection);
        } else {
            // Default sorting
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $samples = $query->paginate(10);

        // Show all pending handovers (anyone can take over, except own handovers)
        $pendingHandovers = SampleHandover::with(['sample.material', 'fromAnalyst'])
            ->where('status', 'pending')
            ->whereNull('to_analyst_id') // Only handovers without assigned analyst
            ->where('from_analyst_id', '!=', auth()->id()) // Exclude own handovers
            ->whereHas('sample', function($q){
                $q->where('sample_type', 'solder');
            })
            ->latest()
            ->get();

        // Show handovers submitted by current user
        $myHandovers = SampleHandover::with(['sample.material', 'toAnalyst'])
            ->where('from_analyst_id', auth()->id())
            ->where('status', 'pending')
            ->whereHas('sample', function($q){
                $q->where('sample_type', 'solder');
            })
            ->latest()
            ->get();

        return view('livewire.sample-solder-submission', [
            'samples' => $samples,
            'pendingHandovers' => $pendingHandovers,
            'myHandovers' => $myHandovers,
        ]);
    }
}
