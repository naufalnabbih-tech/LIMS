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
use App\Livewire\SampleSubmission;
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

// Guest routes (accessible without authentication)
Route::middleware('guest')->group(function () {
    Route::get('/', Login::class)->name('login');
    Route::get('/login', Login::class); // Fallback for Fortify redirects
});

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Raw Material Category Routes
    Route::get('/rawmat-categories', RawMatCategory::class)->name('rawmat-categories');

    // Raw Material Routes
    Route::get('/rawmats', Rawmat::class)->name('rawmats');

    // References
    Route::get('/references', Reference::class)->name('references');

    //Specification
    Route::get('/specification', Specification::class)->name('specifications');

    // Solder Category Routes
    Route::get('/solder-categories', SolderCategory::class)->name('solder-categories');

    // Solder Routes
    Route::get('/solders', Solder::class)->name('solders');

    // Solder References
    Route::get('/solder-references', SolderReference::class)->name('solder-references');

    // Solder Specifications
    Route::get('/solder-specifications', SolderSpecification::class)->name('solder-specifications');

    // Sample Submission Routes
    Route::get('/sample-submissions', SampleSubmission::class)->name('sample-submissions');
    
    // Analysis Page Route
    Route::get('/analysis/{sampleId}', AnalysisPage::class)->name('analysis-page');
    
    // Results Review Page Route
    Route::get('/results-review/{sampleId}', ResultsReviewPage::class)->name('results-review');

    // Sample Analysis Routes (placeholder)
    Route::get('/samples', function () {
        return view('samples.index');
    })->name('samples');

    // Reports Routes (placeholder)
    Route::get('/reports/analysis', function () {
        return view('reports.analysis');
    })->name('reports.analysis');

    Route::get('/reports/audit', function () {
        return view('reports.audit');
    })->name('reports.audit');

    // Settings Routes (placeholder)
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');

    Route::get('/users', UserManagement::class)->name('users');
    
    Route::get('/roles', RoleManagement::class)->name('roles');

    // Instrument Condition Routes
    Route::get('/instrument-conditions', InstrumentConditionManagement::class)->name('instrument-conditions');

    // Instrument Management Routes
    Route::get('/instruments', InstrumentManagement::class)->name('instruments');

    // Thermohygrometer Management Routes
    Route::get('/thermohygrometers', ThermohygrometerManagement::class)->name('thermohygrometers');

    // Thermohygrometer Condition Routes
    Route::get('/thermohygrometer-conditions', ThermohygrometerConditionManagement::class)->name('thermohygrometer-conditions');

    // User Profile Routes (placeholder)
    Route::get('/profile', function () {
        return view('profile.index');
    })->name('profile');
});
