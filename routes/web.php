<?php

use App\Livewire\Dashboard;
use App\Livewire\Rawmat;
use Illuminate\Support\Facades\Route;
use App\Livewire\RawMatCategory;
use App\Livewire\Reference;
use App\Livewire\Specification;
use App\Livewire\Auth\Login;
use App\Livewire\UserManagement;
use App\Livewire\RoleManagement;
use App\Livewire\CoAManagement;
use App\Livewire\CoaDocumentFormatManagement;
use App\Livewire\SampleRawmatSubmission;
use App\Livewire\SampleSolderSubmission;
use App\Livewire\SampleChemicalSubmission;
use App\Livewire\AnalysisPage;
use App\Livewire\ResultsReviewPage;
use App\Livewire\InstrumentConditionManagement;
use App\Livewire\InstrumentManagement;
use App\Livewire\ThermohygrometerManagement;
use App\Livewire\ThermohygrometerConditionManagement;
use App\Livewire\SolderCategory;
use App\Livewire\Solder;
use App\Livewire\SolderReference;
use App\Livewire\SolderSpecification;
use App\Livewire\ChemicalCategory;
use App\Livewire\Chemical;
use App\Livewire\ChemicalReference;
use App\Livewire\ChemicalSpecification;

// Guest routes (accessible without authentication)
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('/login', Login::class); // Fallback for Fortify redirects
    // routes/web.php

    Route::view('/coa', 'livewire.coa');
});

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', Dashboard::class)->name('dashboard')->middleware('permission:view_dashboard');

    // Raw Material Routes
    Route::middleware('permission:manage_raw_material_categories,view_raw_material_categories')->group(function () {
        Route::get('/rawmat-categories', RawMatCategory::class)->name('rawmat-categories');
    });

    Route::middleware('permission:manage_raw_materials,view_raw_materials')->group(function () {
        Route::get('/rawmats', Rawmat::class)->name('rawmats');
    });

    Route::middleware('permission:manage_raw_material_references,view_raw_material_references')->group(function () {
        Route::get('/references', Reference::class)->name('references');
    });

    Route::middleware('permission:manage_raw_material_specifications,view_raw_material_specifications')->group(function () {
        Route::get('/specification', Specification::class)->name('specifications');
    });

    // Solder Routes
    Route::middleware('permission:manage_solder_categories,view_solder_categories')->group(function () {
        Route::get('/solder-categories', SolderCategory::class)->name('solder-categories');
    });

    Route::middleware('permission:manage_solders,view_solders')->group(function () {
        Route::get('/solder', Solder::class)->name('solder');
    });

    Route::middleware('permission:manage_solder_references,view_solder_references')->group(function () {
        Route::get('/solder-references', SolderReference::class)->name('solder-references');
    });

    Route::middleware('permission:manage_solder_specifications,view_solder_specifications')->group(function () {
        Route::get('/solder-specifications', SolderSpecification::class)->name('solder-specifications');
    });

    // Chemical Routes
    Route::middleware('permission:manage_chemical_categories,view_chemical_categories')->group(function () {
        Route::get('/chemical-categories', ChemicalCategory::class)->name('chemical-categories');
    });

    Route::middleware('permission:manage_chemicals,view_chemicals')->group(function () {
        Route::get('/chemicals', Chemical::class)->name('chemicals');
    });

    Route::middleware('permission:manage_chemical_references,view_chemical_references')->group(function () {
        Route::get('/chemical-references', ChemicalReference::class)->name('chemical-references');
    });

    Route::middleware('permission:manage_chemical_specifications,view_chemical_specifications')->group(function () {
        Route::get('/chemical-specifications', ChemicalSpecification::class)->name('chemical-specifications');
    });

    // Sample Submission Routes
    Route::middleware('permission:manage_samples,view_samples')->group(function () {
        Route::get('/sample-rawmat-submissions', SampleRawmatSubmission::class)->name('sample-rawmat-submissions');
        Route::get('/sample-solder-submissions', SampleSolderSubmission::class)->name('sample-solder-submissions');
        Route::get('/sample-chemical-submissions', SampleChemicalSubmission::class)->name('sample-chemical-submissions');
    });

    // Analysis Routes
    Route::middleware('permission:manage_sample_analysis,view_sample_analysis')->group(function () {
        Route::get('/analysis/{sampleId}', AnalysisPage::class)->name('analysis-page');
        Route::get('/results-review/{sampleId}', ResultsReviewPage::class)->name('results-review');
    });

    // Sample Analysis Routes (placeholder)
    Route::middleware('permission:manage_samples,view_samples')->group(function () {
        Route::get('/samples', function () {
            return view('samples.index');
        })->name('samples');
    });

    // Reports Routes
    Route::middleware('permission:view_reports,view_analysis_reports')->group(function () {
        Route::get('/reports/analysis', function () {
            return view('reports.analysis');
        })->name('reports.analysis');
    });

    Route::middleware('permission:view_reports,view_audit_reports')->group(function () {
        Route::get('/reports/audit', function () {
            return view('reports.audit');
        })->name('reports.audit');
    });

    // User Management
    Route::middleware('permission:manage_users,view_users')->group(function () {
        Route::get('/users', UserManagement::class)->name('users');
    });

    // Role Management
    Route::middleware('permission:manage_roles')->group(function () {
        Route::get('/roles', RoleManagement::class)->name('roles');
    });

    // CoA Management
    Route::middleware('permission:manage_coas,view_coas')->group(function () {
        Route::get('/coa-management', CoAManagement::class)->name('coa-management');
        Route::get('/coa-templates', function () {
            return view('livewire.coa-template', [
                'documentNumber' => '[COA NUMBER]',
                'material' => '[BRAND/PRODUCT NAME]',
                'batchLot' => '[LOT NUMBER]',
                'inspectionDate' => '[INSPECTION DATE]',
                'releaseDate' => '[RELEASE DATE]',
                'netWeight' => '[WEIGHT]',
                'poNo' => '[PO NUMBER]',
                'customFields' => [],
                'tests' => [],
                'approver' => '',
                'approverRole' => '',
            ]);
        })->name('coa-templates');
        Route::get('/coa-print/{coaId}', function ($coaId) {
            $coa = \App\Models\CoA::with(['approver.role', 'sample', 'format'])->findOrFail($coaId);

            // Only get custom fields if they were saved at creation time
            // Don't use current format to preserve original CoA data
            $customFieldsData = [];
            $customFieldsDefinition = $coa->data['_custom_fields_definition'] ?? null;

            if (!empty($customFieldsDefinition)) {
                foreach ($customFieldsDefinition as $field) {
                    $fieldKey = $field['key'] ?? '';
                    $fieldLabel = $field['label'] ?? '';
                    $fieldValue = $coa->data[$fieldKey] ?? '';
                    if ($fieldKey && $fieldLabel) {
                        $customFieldsData[] = [
                            'label' => $fieldLabel,
                            'value' => $fieldValue
                        ];
                    }
                }
            }            return view('livewire.coa-template', [
                'coa' => $coa,
                'documentNumber' => $coa->document_number,
                'material' => $coa->data['material'] ?? '',
                'batchLot' => $coa->data['batch_lot'] ?? '',
                'inspectionDate' => $coa->data['inspection_date'] ?? '',
                'releaseDate' => $coa->approved_at?->format('d F Y') ?? '',
                'netWeight' => $coa->net_weight ?? '',
                'poNo' => $coa->po_no ?? '',
                'customFields' => $customFieldsData,
                'tests' => $coa->data['tests'] ?? [],
                'approver' => $coa->approver?->name ?? '',
                'approverRole' => $coa->approver?->role?->display_name ?? '',
                'approverQRSignature' => $coa->approver?->signature_qr_image ?? null,
            ]);
        })->name('coa-print');
        Route::get('/coa-document-formats', CoaDocumentFormatManagement::class)->name('coa-document-formats');
    });

    // Instrument Routes
    Route::middleware('permission:manage_instrument_conditions,view_instrument_conditions')->group(function () {
        Route::get('/instrument-conditions', InstrumentConditionManagement::class)->name('instrument-conditions');
    });

    Route::middleware('permission:manage_instruments,view_instruments')->group(function () {
        Route::get('/instruments', InstrumentManagement::class)->name('instruments');
    });

    // Thermohygrometer Routes
    Route::middleware('permission:manage_thermohygrometers,view_thermohygrometers')->group(function () {
        Route::get('/thermohygrometers', ThermohygrometerManagement::class)->name('thermohygrometers');
    });

    Route::middleware('permission:manage_thermohygrometer_conditions,view_thermohygrometer_conditions')->group(function () {
        Route::get('/thermohygrometer-conditions', ThermohygrometerConditionManagement::class)->name('thermohygrometer-conditions');
    });

    // User Profile Routes
    Route::middleware('auth')->group(function () {
        Route::get('/profile', \App\Livewire\UserProfile::class)->name('profile');
    });
});
