<?php

namespace App\Livewire\SampleChemicalSubmission\Components;

use App\Models\Sample;
use App\Models\CoaDocumentFormat;
use Livewire\Component;

class CoAForm extends Component
{
    // Form visibility
    public $showCoAModal = false;

    // CoA Form Properties
    public $coaData = [];
    public $coaDocumentNumber = '';
    public $coaFullNumber = '';
    public $coaNetWeight = '';
    public $coaPoNo = '';
    public $coaNotes = '';
    public $coaFormatId = null;
    public $currentSampleId = null;
    public $availableFormats = [];
    public $customFieldValues = [];

    protected $listeners = [
        'openCoAForm' => 'handleOpenCoAForm'
    ];

    public function handleOpenCoAForm($data = null)
    {
        // Handle different event payload formats
        if (is_array($data) && isset($data['sampleId'])) {
            $sampleId = $data['sampleId'];
        } elseif (is_numeric($data)) {
            $sampleId = $data;
        } else {
            return; // Invalid data
        }

        $this->openCoAForm($sampleId);
    }

    protected $rules = [
        'coaFormatId' => 'required|exists:coa_document_formats,id',
        'coaDocumentNumber' => 'required|string',
        'coaNetWeight' => 'nullable|string',
        'coaPoNo' => 'nullable|string',
    ];

    public function openCoAForm($sampleId)
    {
        $sample = Sample::with('testResults')->findOrFail($sampleId);

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

        // Prepare test results - explicitly fetch from database
        $tests = [];
        $testResults = \DB::table('test_results')
            ->where('sample_id', $sample->id)
            ->get();

        \Log::info('CoAForm: Sample ID ' . $sample->id . ', found ' . $testResults->count() . ' test results');

        foreach ($testResults as $result) {
            \Log::info('CoAForm: Processing test', [
                'name' => $result->parameter_name,
                'operator' => $result->spec_operator,
                'min' => $result->spec_min_value,
                'max' => $result->spec_max_value,
                'min_not_null' => ($result->spec_min_value !== null),
                'max_not_null' => ($result->spec_max_value !== null),
            ]);

            $testValue = $result->test_value ?? $result->test_value_text ?? 'N/A';
            // Format numeric values to remove trailing zeros
            if (is_numeric($testValue) && strpos($testValue, '.') !== false) {
                $testValue = rtrim(rtrim($testValue, '0'), '.');
            }

            // Determine operator type and structure test data accordingly
            $testData = [
                'name' => $result->parameter_name ?? 'N/A',
                'result' => $testValue,
                'spec_operator' => $result->spec_operator ?? '-',
            ];

            if ($result->spec_operator === '-') {
                // Check if min/max values exist (must use explicit null check, not truthy check)
                if ($result->spec_min_value !== null && $result->spec_max_value !== null) {
                    \Log::info('CoAForm: Setting as RANGE', ['min' => $result->spec_min_value, 'max' => $result->spec_max_value]);
                    $testData['operator'] = 'range';
                    $testData['min'] = $result->spec_min_value;
                    $testData['max'] = $result->spec_max_value;
                    $testData['spec'] = $result->spec_min_value . ' - ' . $result->spec_max_value;
                } else {
                    \Log::info('CoAForm: Setting as NONE (no min/max)');
                    $testData['spec'] = '-';
                    $testData['operator'] = 'none';
                    $testData['value'] = '-';
                }
            } elseif ($result->spec_operator === 'should_be') {
                // For should_be, the specification is the expected text value
                $testData['operator'] = 'should_be';
                $testData['value'] = $result->test_value_text ?? '-';
                $testData['spec'] = $result->spec_text_value ?? '-';
            } elseif ($result->spec_operator === 'range') {
                // For range operator
                $testData['operator'] = 'range';
                $testData['min'] = $result->spec_min_value ?? '-';
                $testData['max'] = $result->spec_max_value ?? '-';
                $testData['spec'] = ($result->spec_min_value ?? '-') . ' - ' . ($result->spec_max_value ?? '-');
            } else {
                // Fallback for other operators
                $testData['operator'] = $result->spec_operator;
                $testData['value'] = $result->spec_min_value ?? '-';
                $testData['spec'] = $result->spec_operator . ' ' . ($result->spec_min_value ?? '') . ($result->spec_max_value ? ' - ' . $result->spec_max_value : '');
            }

            $tests[] = $testData;
        }

        // Pre-populate data from sample
        $this->coaData = [
            'sample_id' => $sample->id,
            'batch_lot' => $sample->batch_lot,
            'material' => $sample->material?->name,
            'reference' => $sample->reference?->name,
            'submission_date' => $sample->submission_time?->format('d-m-Y'),
            'inspection_date' => $sample->approved_at?->format('d F Y'),
            'approved_date' => $sample->approved_at?->format('d-m-Y'),
            'tests' => $tests,
        ];

        $this->currentSampleId = $sampleId;
        $this->showCoAModal = true;
    }

    public function updatedCoaFormatId($value)
    {
        $format = collect($this->availableFormats)->firstWhere('id', (int) $value);
        if (!$format) {
            $format = CoaDocumentFormat::where('is_active', true)->find($value);
        }

        if ($format) {
            $this->applyFormat($format);
        }
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

    public function closeCoAModal()
    {
        $this->showCoAModal = false;
        $this->resetCoAForm();
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
        $this->validate();

        // Update spec field from min/max if they were edited by user
        foreach ($this->coaData['tests'] as $key => $test) {
            if (isset($test['min']) && isset($test['max']) && $test['min'] !== '-' && $test['max'] !== '-') {
                $this->coaData['tests'][$key]['spec'] = $test['min'] . ' - ' . $test['max'];
                $this->coaData['tests'][$key]['operator'] = 'range';
            } elseif (isset($test['value']) && $test['operator'] === 'should_be') {
                $this->coaData['tests'][$key]['spec'] = $test['value'];
            }
        }

        // Prepare data with custom fields definition
        $dataToSave = array_merge($this->coaData, $this->customFieldValues);

        // Include custom fields definition from format
        $format = CoaDocumentFormat::find($this->coaFormatId);
        if ($format && $format->custom_fields) {
            $dataToSave['_custom_fields_definition'] = $format->custom_fields;
        }

        // Create CoA
        \App\Models\CoA::create([
            'document_number' => $this->coaDocumentNumber,
            'format_id' => $this->coaFormatId,
            'sample_id' => $this->currentSampleId,
            'sample_type' => 'chemical',
            'net_weight' => $this->coaNetWeight,
            'po_no' => $this->coaPoNo,
            'status' => 'draft',
            'notes' => $this->coaNotes,
            'data' => $dataToSave,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Certificate of Analysis created successfully!');
        $this->closeCoAModal();
        $this->dispatch('sampleUpdated');
    }

    public function render()
    {
        return view('livewire.sample-chemical-submission.components.coa-form');
    }
}
