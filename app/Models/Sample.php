<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    protected $fillable = [
        'sample_type',
        'category_id',
        'material_id',
        'reference_id',

        //sample details
        'supplier',
        'batch_lot',
        'vehicle_container_number',
        'has_coa',
        'coa_file_path',

        //submission
        'submission_time',
        'entry_time',
        'submitted_by',

        'status_id',

        //analysis workflow
        'analysis_method',
        'primary_analyst_id',
        'secondary_analyst_id',
        'analysis_started_at',
        'analysis_completed_at',


        //review & approval
        'reviewed_at',
        'approved_at',
        'reviewed_by',
        'approved_by',
        //rejection
        'rejected_at',
        'rejected_by',
        //results & notes
        'analysis_results',
        'notes',
    ];

    protected $casts = [
        'has_coa' => 'boolean',
        'submission_time' => 'datetime',
        'entry_time' => 'datetime',
        'analysis_started_at' => 'datetime',
        'analysis_completed_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'analysis_results' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function primaryAnalyst()
    {
        return $this->belongsTo(User::class, 'primary_analyst_id');
    }

    public function secondaryAnalyst()
    {
        return $this->belongsTo(User::class, 'secondary_analyst_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function handovers()
    {
        return $this->hasMany(SampleHandover::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function scopeRawMaterial($q)
    {
        return $q->where('sample_type', 'raw_material');
    }

    public function scopeSolder($q)
    {
        return $q->where('sample_type', 'solder');
    }

    public function isPending()
    {
        return $this->status->name === 'Pending';
    }

    public function isInAnalysis()
    {
        return $this->status->name === 'In Progress';
    }

    public function isCompleted()
    {
        return $this->status->name === 'Completed';
    }

    public function hasActiveHandover()
    {
        return $this->handovers()->where('status', 'pending')->exists();
    }

    public function getActiveHandover()
    {
        return $this->handovers()->where('status', 'pending')->first();
    }
}
