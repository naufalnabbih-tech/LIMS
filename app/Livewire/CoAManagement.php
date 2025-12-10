<?php

namespace App\Livewire;

use App\Models\CoA;
use App\Models\Sample;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class CoAManagement extends Component
    {
        use WithPagination;

        public $showModal = false;
        public $showViewModal = false;
        public $modalMode = 'create'; // create, edit, view

        public $coaId = null;
        public $sampleId = null;
        public $formatId = null;
        public $documentNumber = '';
        public $status = 'draft';
        public $netWeight;
        public $poNo;
        public $notes = '';
        public $data = [];
        public $availableFormats = [];
        public $approvedBy = '';
        public $approverRole = '';
        public $approvedAt = '';    public $searchDocNumber = '';
    public $searchStatus = '';
    public $searchDateFrom = '';
    public $searchDateTo = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = [
        'openCoAForm' => 'openCreateModal',
        'coaCreated' => '$refresh',
        'coaUpdated' => '$refresh'
    ];

    public function openCreateModal($sampleId = null)
    {
        if ($sampleId) {
            $this->sampleId = $sampleId;
            $sample = Sample::with('testResults')->findOrFail($sampleId);

            // Load active formats
            $formats = \App\Models\CoaDocumentFormat::where('is_active', true)->get();
            $this->availableFormats = $formats->toArray();

            if ($formats->isNotEmpty()) {
                $this->formatId = $formats->first()->id;
            }

            // Prepare test results
            $tests = [];
            foreach ($sample->testResults as $result) {
                $testValue = $result->test_value ?? $result->test_value_text ?? 'N/A';
                // Format numeric values to remove trailing zeros
                if (is_numeric($testValue) && strpos($testValue, '.') !== false) {
                    $testValue = rtrim(rtrim($testValue, '0'), '.');
                }
                $tests[] = [
                    'name' => $result->parameter_name ?? 'N/A',
                    'spec' => $result->spec_operator === '-'
                        ? '-'
                        : ($result->spec_operator . ' ' . $result->spec_min_value . ($result->spec_max_value ? ' - ' . $result->spec_max_value : '')),
                    'result' => $testValue
                ];
            }

            // Pre-populate data from sample
            $this->data = [
                'sample_id' => $sample->id,
                'batch_lot' => $sample->batch_lot,
                'material' => $sample->material?->name,
                'reference' => $sample->reference?->name,
                'submission_date' => $sample->submission_time?->format('d-m-Y'),
                'inspection_date' => $sample->approved_at?->format('d F Y'),
                'approved_date' => $sample->approved_at?->format('d-m-Y'),
                'tests' => $tests,
            ];
        }

        $this->modalMode = 'create';
        $this->showModal = true;
    }

    public function editCoA($coaId)
    {
        \Log::info('CoAManagement@editCoA called', ['coaId' => $coaId]);
        $coa = CoA::findOrFail($coaId);

        // Load active formats for document number selection
        $formats = \App\Models\CoaDocumentFormat::where('is_active', true)->get();
        $this->availableFormats = $formats->map(function ($format) {
            return [
                'id' => $format->id,
                'name' => $format->name ?? ('Format ' . $format->id),
                'document_number' => $format->generateDocumentNumber(),
            ];
        })->toArray();

        $this->coaId = $coa->id;
        $this->formatId = $coa->format_id;
        $this->documentNumber = $coa->document_number;
        $this->status = $coa->status;
        $this->netWeight = $coa->net_weight;
        $this->poNo = $coa->po_no;
        $this->notes = $coa->notes;
        $this->data = $coa->data ?? [];
        $this->sampleId = $coa->sample_id;

        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    public function viewCoA($coaId)
    {
        \Log::info('CoAManagement@viewCoA called', ['coaId' => $coaId]);
        $coa = CoA::with(['sample', 'approver.role', 'format'])->findOrFail($coaId);

        $this->coaId = $coa->id;
        $this->formatId = $coa->format_id;
        $this->documentNumber = $coa->document_number;
        $this->status = $coa->status;
        $this->netWeight = $coa->net_weight;
        $this->poNo = $coa->po_no;
        $this->notes = $coa->notes;
        $this->data = $coa->data ?? [];
        $this->sampleId = $coa->sample_id;
        $this->approvedBy = $coa->approver?->name ?? '-';
        $this->approverRole = $coa->approver?->role?->display_name ?? '-';
        $this->approvedAt = $coa->approved_at?->format('d F Y') ?? '-';

        $this->modalMode = 'view';
        $this->showViewModal = true;
    }

    public function saveCoA()
    {
        try {
            \Log::info('CoAManagement@saveCoA called', ['mode' => $this->modalMode, 'coaId' => $this->coaId]);

            // Validation rules
            $rules = [
                'documentNumber' => 'required|string|min:3',
                'netWeight' => 'nullable|string|max:255',
                'poNo' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
                'sampleId' => 'required_if:modalMode,create|exists:samples,id'
            ];

            $this->validate($rules);

            if ($this->modalMode === 'create') {
                // Prepare data with custom fields definition from format
                $dataToSave = $this->data;

                // Include custom fields definition from current format
                $format = \App\Models\CoaDocumentFormat::find($this->formatId);
                if ($format && $format->custom_fields) {
                    $dataToSave['_custom_fields_definition'] = $format->custom_fields;
                }

                CoA::create([
                    'document_number' => $this->documentNumber,
                    'sample_id' => $this->sampleId,
                    'format_id' => $this->formatId,
                    'sample_type' => Sample::find($this->sampleId)->sample_type,
                    'status' => 'draft',
                    'net_weight' => $this->netWeight,
                    'po_no' => $this->poNo,
                    'notes' => $this->notes,
                    'data' => $dataToSave
                ]);

                session()->flash('message', 'CoA created successfully!');
            } else {
                $coa = CoA::findOrFail($this->coaId);

                // In edit mode, only update data that was originally present
                // Don't add new custom fields to old CoAs
                $dataToSave = $this->data;

                // Preserve the original _custom_fields_definition if it exists
                if (isset($coa->data['_custom_fields_definition'])) {
                    $dataToSave['_custom_fields_definition'] = $coa->data['_custom_fields_definition'];
                }

                $coa->update([
                    'net_weight' => $this->netWeight,
                    'po_no' => $this->poNo,
                    'notes' => $this->notes,
                    'data' => $dataToSave
                ]);

                session()->flash('message', 'CoA updated successfully!');
            }

            $this->resetForm();
            $this->dispatch('coaUpdated');
        } catch (\Exception $e) {
            \Log::error('CoAManagement@saveCoA error', ['message' => $e->getMessage()]);
            session()->flash('error', 'Error saving CoA: ' . $e->getMessage());
        }
    }

    public function approveCoA($coaId)
    {
        try {
            \Log::info('CoAManagement@approveCoA called', ['coaId' => $coaId]);
            $coa = CoA::findOrFail($coaId);

            if (!auth()->user()->hasPermission('approve_coa')) {
                session()->flash('error', 'You do not have permission to approve CoA.');
                return;
            }

            // Auto-generate document number with sequence if not already set with proper format
            $documentNumber = $coa->document_number;
            if (!$documentNumber || !preg_match('/^\d+\//', $documentNumber)) {
                if ($coa->format) {
                    // Get current sequence for this format in current month
                    $sequence = $coa->format->getNextSequence();
                    $docNumber = $coa->format->generateDocumentNumber();
                    $documentNumber = "{$sequence}/{$docNumber}";
                }
            }

            $coa->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'release_date' => now(),
                'document_number' => $documentNumber
            ]);

            session()->flash('message', 'CoA approved successfully!');

            // Refresh view modal if open
            if ($this->showViewModal) {
                $this->viewCoA($coaId);
            }

            $this->dispatch('coaUpdated');
        } catch (\Exception $e) {
            \Log::error('CoAManagement@approveCoA error', ['message' => $e->getMessage()]);
            session()->flash('error', 'Error approving CoA: ' . $e->getMessage());
        }
    }



    public function submitForReview($coaId)
    {
        try {
            \Log::info('CoAManagement@submitForReview called', ['coaId' => $coaId]);
            $coa = CoA::findOrFail($coaId);

            if ($coa->isDraft()) {
                $coa->update([
                    'status' => 'pending_review',
                ]);

                session()->flash('message', 'CoA submitted for review.');
                $this->dispatch('coaUpdated');
            }
        } catch (\Exception $e) {
            \Log::error('CoAManagement@submitForReview error', ['message' => $e->getMessage()]);
            session()->flash('error', 'Error submitting CoA: ' . $e->getMessage());
        }
    }

    public function printCoA($coaId)
    {
        try {
            \Log::info('CoAManagement@printCoA called', ['coaId' => $coaId]);
            $coa = CoA::findOrFail($coaId);

            if (!auth()->user()->hasPermission('approve_coa')) {
                session()->flash('error', 'You do not have permission to print CoA.');
                return;
            }

            // Update status to printed
            $coa->update([
                'status' => 'printed'
            ]);

            session()->flash('message', 'CoA printed successfully!');

            // Open print template in new window
            $printUrl = route('coa-print', ['coaId' => $coaId]);
            $this->dispatch('openPrintWindow', url: $printUrl);
        } catch (\Exception $e) {
            \Log::error('CoAManagement@printCoA error', ['message' => $e->getMessage()]);
            session()->flash('error', 'Error printing CoA: ' . $e->getMessage());
        }
    }

    public function deleteCoA($coaId)
    {
        try {
            \Log::info('CoAManagement@deleteCoA called', ['coaId' => $coaId]);
            CoA::findOrFail($coaId)->delete();
            session()->flash('message', 'CoA deleted successfully!');
            $this->dispatch('coaUpdated');
        } catch (\Exception $e) {
            \Log::error('CoAManagement@deleteCoA error', ['message' => $e->getMessage()]);
            session()->flash('error', 'Error deleting CoA: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'coaId',
            'sampleId',
            'formatId',
            'documentNumber',
            'status',
            'netWeight',
            'poNo',
            'notes',
            'data',
            'showModal',
            'availableFormats'
        ]);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'pending_review' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'printed' => 'bg-blue-100 text-blue-800',
            'archived' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function render()
    {
        $query = CoA::with(['sample', 'approver']);

        // Apply filters
        if ($this->searchDocNumber) {
            $query->where('document_number', 'like', "%{$this->searchDocNumber}%");
        }

        if ($this->searchStatus) {
            $query->where('status', $this->searchStatus);
        }

        if ($this->searchDateFrom && $this->searchDateTo) {
            $query->whereBetween('release_date', [
                Carbon::parse($this->searchDateFrom)->startOfDay(),
                Carbon::parse($this->searchDateTo)->endOfDay()
            ]);
        }

        // Sort
        $coas = $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);

        return view('livewire.coa-management', [
            'coas' => $coas
        ]);
    }
}
